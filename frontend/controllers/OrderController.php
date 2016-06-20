<?php

namespace frontend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use frontend\components\ebayapi\EbayOrder;
use yii\web\Response;
use frontend\models\orders\OrderLog;
use frontend\models\ebayaccounts\EbayAccount;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use frontend\models\orders\EOrder;
use frontend\models\orders\EOrderSearch;
use frontend\models\orders\EbayTransaction;
use frontend\components\ebayapi\EbayListing;
use kartik\mpdf\Pdf;
use frontend\models\UserSetting;
use frontend\components\JHelper;
use frontend\components\EparcelHelper;
use yii\base\ErrorException;

class OrderController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access'=>[
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('ebayuser');
                        }
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new EOrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 发货扫描页面
     */
    public function actionDispatchIndex()
    {
      return $this->render('dispatch_index');
    }

    /**
     * Loop orders and get shipping label
     */
    private function getShippingLabel($orders){
      $label = '';
      //return count($orders);
      $userSetting = UserSetting::findOne(Yii::$app->user->id);
      $orderIndex = 0;

      $eParcelHelper = new EparcelHelper();

      foreach ($orders as $key => $order) {
        $packSign = [
          'eParcel'=>false,
          'fastway'=>false,
          'express'=>false,
          'buyerCount'=>1,
          'checkoutMessage'=>false,
          'weight'=>0,
          'totalCost'=>0,
        ];
        $transLabel = '';

        foreach ($order['ebayTransactions'] as $transaction) {
          if($product = $transaction->getProduct()->one()){
            $packSign['weight'] += ($product['weight']*$transaction['qty_purchased']);
            $packSign['totalCost'] += ($product['cost']*$transaction['qty_purchased']);
          }else{
            $transLabel .= "Error! Can't find the product ".$transaction['item_sku'];
          }
          $transLabel .= $this->renderPartial('_translabel',['transaction'=>$transaction]);
        }
        $packSign['weight'] = round($packSign['weight']/1000,2);

        $packSign['fastway'] = ($userSetting->fastway_indicator&&JHelper::isFastwayAvailable($order['recipient_postcode']));

        if ($packSign['totalCost']>=$userSetting->min_cost_tracking) {
          $packSign['eParcel'] = true;
        }
        if($order['shipping_service'] == 'AU_ExpressDelivery' || $order['shipping_service'] =='AU_Express' || $order['shipping_service'] == 'AU_ExpressWithInsurance'){
          $packSign['express'] = true;
        }
        if($order->buyerCount>1){
          $packSign['buyerCount'] = $order->buyerCount;
        }
        if($order['checkout_message']!=NULL){
          $packSign['checkoutMessage'] = true;
        }
        $label .=$this->renderPartial('label',['order'=>$order,'transLabel'=>$transLabel,'packSign'=>$packSign, 'orderIndex'=>$orderIndex]);
        $orderIndex++;

        $eParcelHelper->addExcelRow($order, $packSign['weight']);

      }

      return ['label'=>$label,'excelObj'=>$eParcelHelper];
    }

    public function actionDownloadLabels()
    {
      $post = Yii::$app->request->post();
      $searchModel = new EOrderSearch();
      if (isset($post['ebayIDArr'])) {
        $nonLabeledOrders = $searchModel->getNonLabeled($post['ebayIDArr']);
      }else{
        return Yii::t('app\order', 'Please select at least one ebay account', []);
      }

      if($nonLabeledOrders){
        $shippingLabels = $this->getShippingLabel($nonLabeledOrders);
        //return $shippingLabels['label'];
      }else{
        return 'No Orders To Be Labeled';
      }

      //create a template dir, should delete files periodly
      $tmpDir = Yii::$app->params['labelDirectory'].Yii::$app->user->id;
      if (!file_exists($tmpDir) && !mkdir($tmpDir, 0755, true)) {
          throw new \yii\web\HttpException(404, 'No label directories');
      }

      $labelFile = $tmpDir."/label.pdf";
      $excelFile = $tmpDir.'/eparcel.xlsx';

      $pdf = new Pdf([
            // set to use core fonts only
            'format'=>['105','148'],
            'mode' => Pdf::MODE_UTF8,
            'marginLeft' => '2',
            'marginRight' => '2',
            'marginTop' => '13',
            'marginBottom' => '0',
            'filename'=>$labelFile,
            'options'=>[
              'showImageErrors' => true,
            ],
            'destination'=>Pdf::DEST_FILE,
            //'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $shippingLabels['label'],
          ]);
      //return $pdf->render();
      $objWriter = \PHPExcel_IOFactory::createWriter($shippingLabels['excelObj']->objPHPExcel, 'Excel2007');
      try {
        $pdf->render();
        $objWriter->save($excelFile);
      } catch (ErrorException $e) {
        throw new \yii\web\HttpException(404, 'Can not create PDF OR Excel File');
      }

      $zip = new \ZipArchive();
      $filename = $tmpDir.'/'."orders-".date('Y-m-d-H-i-s').".zip";

      try {
        $zip->open($filename, \ZipArchive::CREATE);
        $zip->addFile($labelFile,'labels-'.date('Y-m-d-H-i-s').'.pdf');
        $zip->addFile($excelFile,'eparcel-'.date('Y-m-d-H-i-s').'.xlsx');
        $zip->close();
      } catch (ErrorException $e) {
        throw new \yii\web\HttpException(404, 'Can not create Zip File');
      }

      return \Yii::$app->response->sendFile($filename);
    }

    public function actionFetchIndex(){
      $query = (new \yii\db\Query())
            ->select([
              'ebay_account.seller_id',
              'ebay_account.id AS ebay_id',
              'order_log.order_qty',
              'order_log.create_from',
              'order_log.create_to',
              'order_log.complete_at',
              'order_log.status',
              'sum(case when ebay_order.status = 0 then 1 else 0 end) AS "Not Shipped"',
              'sum(case when ebay_order.label = 0 then 1 else 0 end) AS "Not Label"',
              //'sum(case when ebay_order.status = -1 OR ebay_order.paid_time IS NULL then 1 else 0 end) AS "Not Paid"',
              'sum(case when ebay_order.status = -1 then 1 else 0 end) AS "Not Paid"',
            ])
            ->from('ebay_account')
            ->leftJoin('order_log','ebay_account.id = order_log.ebay_id')
            ->leftJoin('ebay_order','ebay_account.id = ebay_order.ebay_id')
            ->where(['ebay_account.user_id'=>Yii::$app->user->id])
            ->groupBy('ebay_account.id')
            ;
      $dataProvider = new ActiveDataProvider([
          'query' => $query,
      ]);
      $model = new EOrder();
      return $this->render('fetch_index',[
        'dataProvider' => $dataProvider,
        'model' => $model,
      ]);
    }

    public function actionTestOrder(){
      $createFrom=new \DateTime('2016-05-18');
      $createTo=new \DateTime('2016-05-26');
      $ebayOrder = new EbayOrder(3);
      $preFetch = $ebayOrder->getPreFetchInfo($createFrom,$createTo);
      for ($i=1; $i<=7; $i++) {
        $orders = $ebayOrder->mainFetch($createFrom,$createTo,$i);
        foreach ($orders['orders'] as $order) {
          // echo "<pre>";
          //   echo var_dump($order)."<br/>";
          //   echo "</pre>";
          echo var_dump($order->OrderID.":".$order->PaidTime->format('Y-m-d H:i:s'))."<br/>";
        }
      }
      // echo "<pre>";
      // echo var_dump($preFetch);
      // echo "</pre>";
      return 1;
      $orders = $ebayOrder->mainFetch($createFrom,$createTo,4);
      foreach ($orders['orders'] as $order) {
        // echo "<pre>";
        //   echo var_dump($order)."<br/>";
        //   echo "</pre>";
        echo var_dump($order->ShippingServiceSelected->ShippingService)."<br/>";
      }
      return 1;
      echo "<pre>";
      return print_r($orders);
      echo "</pre>";
    }


    private function saveOrders($orders, $ebayID)
    {
      $result = [];
      foreach ($orders as $order) {
          $orderModal = EOrder::findOne(['ebay_order_id'=>$order->OrderID]);
          if($orderModal===NULL){
            $orderModal = new EOrder();
          }

          if(isset($order->ShippedTime)){
            $orderModal->status = EOrder::STATUS_SHIPPED;
            $orderModal->shipped_time = $order->ShippedTime->format('Y-m-d H:i:s');
          }else{
            $orderModal->status = 0;
            $orderModal->shipped_time = NULL;
          }

          if(isset($order->PaidTime)){
            $orderModal->paid_time = $order->PaidTime->format('Y-m-d H:i:s');
          }else{//order not paid
            $orderModal->paid_time = NULL;
          }

          $orderModal->ebay_id = $ebayID;
          $orderModal->user_id = Yii::$app->user->id;
          $orderModal->ebay_order_id = $order->OrderID;
          $orderModal->ebay_seller_id = $order->SellerUserID;
          $orderModal->sale_record_number = $order->ShippingDetails->SellingManagerSalesRecordNumber;
          //$orderModal->sale_record_number = 'sf';
          $orderModal->buyer_id = $order->BuyerUserID;
          $orderModal->total = $order->Total->value;
          $orderModal->created_time = $order->CreatedTime->format('Y-m-d H:i:s');
          $orderModal->recipient_name = $order->ShippingAddress->Name;
          $orderModal->recipient_phone = $order->ShippingAddress->Phone;
          $orderModal->recipient_address1 = $order->ShippingAddress->Street1;
          $orderModal->recipient_address2 = $order->ShippingAddress->Street2;
          $orderModal->recipient_city = $order->ShippingAddress->CityName;
          $orderModal->recipient_state = $order->ShippingAddress->StateOrProvince;
          $orderModal->recipient_postcode =$order->ShippingAddress->PostalCode;
          $orderModal->checkout_message = $order->BuyerCheckoutMessage;
          $orderModal->shipping_service = $order->ShippingServiceSelected->ShippingService;
          if(!$orderModal->save()){
            foreach ($orderModal->errors as $errorArray) {
              foreach ($errorArray as $error) {
                $result[] = $error;
              }
            }
            return $result;
          }else{
            foreach ($order->TransactionArray->Transaction as $transaction) {
              $transactionModal=EbayTransaction::findOne($transaction->TransactionID);
              if($transactionModal===NULL){
                $transactionModal = new EbayTransaction();
              }
              //$transactionModal = new EbayTransaction();
              $transactionModal->transaction_id = $transaction->TransactionID;
              $transactionModal->ebay_order_id = $orderModal->id;
              $transactionModal->buyer_email = $transaction->Buyer->Email;
              $transactionModal->created_date = $transaction->CreatedDate->format('Y-m-d H:i:s');
              $transactionModal->final_value_fee = $transaction->FinalValueFee->value;
              $transactionModal->item_id = $transaction->Item->ItemID;
              $transactionModal->item_sku = $transaction->Item->SKU;
              $transactionModal->item_title = $transaction->Item->Title;
              $transactionModal->qty_purchased = $transaction->QuantityPurchased;

              $transactionModal->sale_record_number = $transaction->ShippingDetails->SellingManagerSalesRecordNumber;
              if(isset($transaction->ShippingDetails->ShipmentTrackingDetails)){
                $transactionModal->tracking_number = $transaction->ShippingDetails->ShipmentTrackingDetails['0']->ShipmentTrackingNumber;
              }
              if(isset($transaction->ShippingDetails->ShipmentTrackingDetails)){
                $transactionModal->shipping_carrier = $transaction->ShippingDetails->ShipmentTrackingDetails['0']->ShippingCarrierUsed;
              }
              $transactionModal->transaction_price = $transaction->TransactionPrice->value;
              if(isset($transaction->Variation->VariationTitle)){
                $transactionModal->variation = $transaction->Variation->VariationTitle;
              }

              if(!$transactionModal->save()){
                foreach ($transactionModal->errors as $errorArray) {
                  foreach ($errorArray as $error) {
                    $result[] = $error;
                  }
                }
                return $result;
              }
            }//end transaction loop
          }//end else
        }//end orders loop

    }

    public function actionDownload()
    {
      if (Yii::$app->request->isAjax) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $ebayOrderApi = new EbayOrder($post['ebayID']);
        if($orderLog=$this->findModel($post['ebayID'])){//not first time download
          if($orderLog->status===OrderLog::STATUS_DOWNLOAD_DONE){//上次成功完成了下载订单，这次的订单起始时间就是上次的截止时间
            $orderLog->create_from = $orderLog->create_to;
            $orderLog->create_to = $ebayOrderApi->ebayOfficialTime()->format('Y-m-d H:i:s');//can not save, must format to string to save
          }
        }else{//first time download orders for this ebay acc id
          $ebayTimeNow = $ebayOrderApi->ebayOfficialTime();
          $createdFrom = clone $ebayTimeNow;
          //$createdFrom = $ebayTimeNow->sub(new \DateInterval('P2D'));//2 days before ebay time now
          $orderLog = new OrderLog();
          $orderLog->order_qty = 0;
          $orderLog->ebay_id = $post['ebayID'];
          //$orderLog->status = OrderLog::STATUS_DOWNLOAD_INIT;
          $orderLog->create_from = $createdFrom->sub(new \DateInterval('P2D'))->format('Y-m-d H:i:s');
          $orderLog->create_to = $ebayTimeNow->format('Y-m-d H:i:s');
        }
        //api call to download orders
        //$result[orders][moreOrders][orderCounts]
        $result = $ebayOrderApi->getOrdersByTime(new \DateTime($orderLog->create_from), new \DateTime($orderLog->create_to),$post['pageNum']);
        if(isset($result['Error'])){
          return $result;
        }
        //save orders info to database
        if($result['Error']=$this->saveOrders($result['orders'],$post['ebayID'])){
          return $result;
        }

        if(!$result['moreOrders']){//no more orders
          $orderLog->status = OrderLog::STATUS_DOWNLOAD_DONE;
          $orderLog->order_qty = $result['orderCounts'];
        }else{//more pages to download
          $orderLog->status = OrderLog::STATUS_DOWNLOAD_INIT;
          $orderLog->order_qty = 0;
        }
        $orderLog->complete_at = date('Y-m-d H:i:s',time());
        if($orderLog->save()){
          return ['moreOrders'=>$result['moreOrders'],'orderCounts'=>$result['orderCounts']];
        }else{
          $result['Error'][]='Order Log failed to save';
          foreach ($orderLog->errors as $errorArray) {
            foreach ($errorArray as $error) {
              $result['Error'][] = $error;
            }
          }
        }


      }else{
        return false;
      }
    }
    /**
     * 去ebay更新下过单却没有付款的订单，这些订单已经存在数据库中，但状态是-1，意思是没有付款
     */
    public function actionUpdateNotPaid()
    {
      if (Yii::$app->request->isAjax) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $notPaidOrders = EOrder::find()->select(['ebay_order_id'])->where(['status'=>EOrder::STATUS_NOT_PAID,'paid_time'=>null,'ebay_id'=>$post['ebayID']])->asArray()->all();
        //$notPaidOrders = EOrder::find()->select(['ebay_order_id'])->where(['status'=>EOrder::STATUS_NOT_PAID,'ebay_id'=>$post['ebayID']])->asArray()->all();
        $notPaidOrderIDArr = ArrayHelper::getColumn($notPaidOrders,'ebay_order_id');
        $ebayOrderApi = new EbayOrder($post['ebayID']);
        $result = $ebayOrderApi->getOrdersByID($notPaidOrderIDArr,$post['pageNum']);
        if(isset($result['Error'])){
          return $result;
        }
        //update unpaid orders
        if($result['Error']=$this->saveOrders($result['orders'],$post['ebayID'])){
          return $result;
        }
        return ['moreOrders'=>$result['moreOrders']];
      }else{
        // $notPaidOrders = EOrder::find()->select(['ebay_order_id'])->where(['status'=>EOrder::STATUS_NOT_PAID,'paid_time'=>null,'ebay_id'=>3])->asArray()->all();
        // $notPaidOrderIDArr = ArrayHelper::getColumn($notPaidOrders,'ebay_order_id');
        // $ebayOrderApi = new EbayOrder(3);
        // $result = $ebayOrderApi->getOrdersByID($notPaidOrderIDArr,1);
        // echo "<pre>";
        // echo print_r($result);
        // echo "</pre>";
        return false;
      }


    }

    public function actionItemPic(){
      if(Yii::$app->request->isAjax){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post=Yii::$app->request->post();
        $ebayListing = new EbayListing($post['ebayID']);
        $itemPicUrl = $ebayListing->getItemPicUrl($post['itemID']);
        $transaction = $this->findTransaction($post['transactionID']);
        $transaction->image=$itemPicUrl;
        if($transaction->save()){
          return $itemPicUrl;
        }

      }
    }
    protected function findTransaction($id)
    {
      if (($model = EbayTransaction::findOne($id)) !== null) {
          return $model;
      } else {
          throw new NotFoundHttpException('The requested page does not exist.');
      }
    }
    protected function findModel($id)
    {
        $ebayAccount = EbayAccount::findOne($id);
        if(Yii::$app->user->can('ebaycontrol',['userID'=>$ebayAccount->user_id])){
          if (($model = OrderLog::findOne($id)) !== null) {
              return $model;
          } else {
            return null;
          }
        }else{
          throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findOrderModel($id)
    {

        if (($model = EOrder::findOne($id)) !== null && Yii::$app->user->can('ebaycontrol',['userID'=>$model->user_id])) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
