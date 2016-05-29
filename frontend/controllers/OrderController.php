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
use frontend\models\orders\EOrder;
use frontend\models\orders\EOrderSearch;
use frontend\models\orders\EbayTransaction;
use frontend\components\ebayapi\EbayListing;
use kartik\mpdf\Pdf;
use frontend\models\UserSetting;
use frontend\components\JHelper;
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
     * Loop orders and get shipping label
     */
    private function getShippingLabel($orders){
      $label = '';
      $userSetting = UserSetting::findOne(Yii::$app->user->id);
      $packSign = [
        'eParcel'=>false,
        'fastway'=>false,
        'buyerCount'=>1,
        'checkoutMessage'=>false,
      ];
      foreach ($orders as $order) {
        $transLabel = '';
        $transactions = $order->getEbayTransactions()->all();
        $totalCost = 0;
        $weight = 0;

        foreach ($transactions as $transaction) {
          if($product = $transaction->getProduct()->one()){
            $weight += ($product['weight']*$transaction['qty_purchased']);
            $totalCost += ($product['cost']*$transaction['qty_purchased']);
          }else{
            $transLabel .= "Error! Can't find the product ".$transaction['item_sku'];
          }
        }
        $weight = round($weight/1000,2);
        if($userSetting->fastway_indicator&&JHelper::isFastwayAvailable($order['recipient_postcode'])){
          $packSign['fastway'] = true;
        }
        if ($totalCost>=$userSetting->min_cost_tracking) {
          $packSign['eParcel'] = true;
        }
        if($order['buyer_count']>1){
          $packSign['buyerCount'] = $order['buyer_count'];
        }
        if($order['checkout_message']!=NULL){
          $packSign['checkoutMessage'] = true;
        }

      }
    }
    public function actionDownloadLabel()
    {
      $post = Yii::$app->request->post();
      $notLabelOrder = EOrder::find()->where(['label'=>0,'status'=>0])->all();
      //$subQuery = (new \yii\db\Query())->select(['t.buyer_id','COUNT(t.buyer_id) as buyer_count'])->from('ebay_order t')->groupBy(['t.buyer_id']);
      // $notLabelOrder = (new \yii\db\Query())
      //               ->select(['x.*','y.buyer_count'])
      //               ->from('ebay_order x')
      //               ->where(['x.label'=>0,'x.status'=>0])
      //               ->leftJoin(['y' => $subQuery],'y.buyer_id=x.buyer_id')
      //               ->all();
      // echo "<pre>";
      // return print_r($notLabelOrder);
      // echo "</pre>";
      //return $this->getShippingLabel($notLabelOrder);
      //$label = $this->getShippingLabel($notLabelOrder);
      $label = '';
      $objPHPExcel = \PHPExcel_IOFactory::load('./labels/'.'eparcel_template20151023.xlsx');
      $excelRow = 2;
      $trackingThreshold = UserSetting::findOne(Yii::$app->user->id)->min_cost_tracking;
      foreach ($notLabelOrder as $order) {
        $transactionArray=$order->getEbayTransactions()->all();
        $transLabel = '';
        $packSign = '';
        $total = 0;
        $weight = 0;
        foreach ($transactionArray as $transaction) {
          if($product = $transaction->getProduct()->one()){
            $weight += ($product['weight']*$transaction['qty_purchased']);
            $total += ($product['cost']*$transaction['qty_purchased']);
            if($total>$trackingThreshold){
              $packSign = 'TRACKING';
            }

          }

          $transLabel .= $this->renderPartial('_translabel',['transaction'=>$transaction]);
        }
        $weight = round($weight/1000,2);
        if($packSign==='TRACKING'){
          $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A'.$excelRow, $weight)
                 ->setCellValue('B'.$excelRow, $order['recipient_name'])
                 ->setCellValue('D'.$excelRow, $order['recipient_phone'])
                 ->setCellValue('F'.$excelRow, $order['recipient_address1'])
                 ->setCellValue('G'.$excelRow, $order['recipient_address2'])
                 ->setCellValue('I'.$excelRow, $order['recipient_city'])
                 ->setCellValue('J'.$excelRow, $order['recipient_state'])
                 ->setCellValue('K'.$excelRow, $order['recipient_postcode'])
                 ->setCellValue('L'.$excelRow, $order['ebay_order_id'])
                 ->setCellValue('N'.$excelRow, $order['buyer_id'])
                 ;
          $excelRow++;
        }
        $label .= $this->renderPartial('label',['order'=>$order,'transLabel'=>$transLabel,'packSign'=>$packSign,'weight'=>$weight]);
      }
      $tmpDir = './labels/'.Yii::$app->user->id;
      $labelFile = $tmpDir."/label.pdf";
      $excelFile = $tmpDir.'/eparcel.xlsx';
      if (!file_exists($tmpDir)) {
          mkdir($tmpDir, 0777, true);
      }
      $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
      $objWriter->save($excelFile);

      //return $label;

      // $content = $this->renderPartial('label',[
      //   'orders'=>$notLabelOrder,
      // ]);

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
            // your html content input
            'content' => $label,
          ]);
      $pdf->render();
      //return $pdf->render();

      $zip = new \ZipArchive();
      $filename = $tmpDir.'/'."orders-".date('Y-m-d-H-i-s').".zip";
      if ($zip->open($filename, \ZipArchive::CREATE)!==TRUE) {
          exit("cannot open <$filename>\n");
      }
      $zip->addFile($labelFile,'labels-'.date('Y-m-d-H-i-s').'.pdf');
      $zip->addFile($excelFile,'eparcel-'.date('Y-m-d-H-i-s').'.xlsx');
      $zip->close();

      // return $this->render('label',[
      //   'orders'=>$notLabelOrder,
      // ]);
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
              'sum(case when ebay_order.status = -1 AND ebay_order.paid_time IS NULL then 1 else 0 end) AS "Not Paid"',
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
    public function actionTime()
    {
      if(Yii::$app->request->isAjax){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post=Yii::$app->request->post();
        $ebayOrder = new EbayOrder($post['ebayID']);
        $ebayTime = $ebayOrder->ebayOfficialTime();
        return [date('Y-m-d H:i:s',strtotime($ebayTime->format('Y-m-d H:i:s'))),date('Y-m-d H:i:s',time()),date('Y-m-d H:i:s',$ebayTime->getTimestamp())];
      }
    }

    public function actionSaveLog(){
      if(Yii::$app->request->isAjax){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post=Yii::$app->request->post();
        $orderLog=$this->findModel($post['ebayID']);
        $orderLog->status = OrderLog::STATUS_DONE_FETCH;
        if($orderLog->save()){
          return true;
        }
      }
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
    public function actionMainFetch(){
        if(Yii::$app->request->isAjax){
          Yii::$app->response->format = Response::FORMAT_JSON;
          $post=Yii::$app->request->post();
          $result = [];
          $ebayOrder = new EbayOrder($post['ebayID']);
          $orderLog=$this->findModel($post['ebayID']);
          $createFrom=new \DateTime($orderLog->create_from);
          $createTo=new \DateTime($orderLog->create_to);
          $orders = $ebayOrder->mainFetch($createFrom,$createTo,$post['pageNumber']);
          //return var_dump($orders['hasMoreOrders']);
          if(!isset($orders['Error'])){
            $result['status']='success';
            $result['message']='Connection Success';
             //$result['test']=var_dump($orders['orders']);
            //return $result;
            foreach ($orders['orders'] as $order) {
              $thisOrder = EOrder::findOne(['ebay_order_id'=>$order->OrderID]);
              if($thisOrder===null){
                $thisOrder = new EOrder();
              }
              //$thisOrder = new EOrder();
              if(isset($order->ShippedTime)){
                $thisOrder->status = 1;
                $thisOrder->shipped_time = $order->ShippedTime->format('Y-m-d H:i:s');
              }else{
                $thisOrder->status = 0;
                $thisOrder->shipped_time = NULL;
              }
              $thisOrder->ebay_id = $post['ebayID'];
              $thisOrder->user_id = Yii::$app->user->id;
              $thisOrder->ebay_order_id = $order->OrderID;
              $thisOrder->ebay_seller_id = $order->SellerUserID;
              $thisOrder->sale_record_number = $order->ShippingDetails->SellingManagerSalesRecordNumber;
              //$thisOrder->sale_record_number = 'sf';
              $thisOrder->buyer_id = $order->BuyerUserID;
              $thisOrder->total = $order->Total->value;
              $thisOrder->created_time = $order->CreatedTime->format('Y-m-d H:i:s');
              if(isset($order->PaidTime)){
                $thisOrder->paid_time = $order->PaidTime->format('Y-m-d H:i:s');
              }else{//order not paid
                $thisOrder->paid_time = NULL;
              }

              $thisOrder->recipient_name = $order->ShippingAddress->Name;
              $thisOrder->recipient_phone = $order->ShippingAddress->Phone;
              $thisOrder->recipient_address1 = $order->ShippingAddress->Street1;
              $thisOrder->recipient_address2 = $order->ShippingAddress->Street2;
              $thisOrder->recipient_city = $order->ShippingAddress->CityName;
              $thisOrder->recipient_state = $order->ShippingAddress->StateOrProvince;
              $thisOrder->recipient_postcode =$order->ShippingAddress->PostalCode;
              $thisOrder->checkout_message = $order->BuyerCheckoutMessage;
              $thisOrder->shipping_service = $order->ShippingServiceSelected->ShippingService;
              if(!$thisOrder->save()){
                // foreach ($thisOrder->errors as $attribute => $error ) {
                //   $savingErrorMessage = "Buyer ID: ".$order->BuyerUserID."<br/>";
                //   foreach ($error as $errorMessage) {
                //     $savingErrorMessage .= $errorMessage;
                //   }
                //   $result['savingError'][] = $savingErrorMessage;
                // }
                //$result['savingError'][]=$thisOrder->errors." Buyer ID: ".$thisOrder->BuyerUserID;
                $result['savingError'][]=[$order->OrderID=>$thisOrder->errors];
              }else{
                foreach ($order->TransactionArray->Transaction as $transaction) {
                  if(!$thisTransaction=EbayTransaction::findOne($transaction->TransactionID)){
                    $thisTransaction = new EbayTransaction();
                  }
                  //$thisTransaction = new EbayTransaction();
                  $thisTransaction->transaction_id = $transaction->TransactionID;
                  $thisTransaction->ebay_order_id = $thisOrder->id;
                  $thisTransaction->buyer_email = $transaction->Buyer->Email;
                  $thisTransaction->created_date = $transaction->CreatedDate->format('Y-m-d H:i:s');
                  $thisTransaction->final_value_fee = $transaction->FinalValueFee->value;
                  $thisTransaction->item_id = $transaction->Item->ItemID;
                  $thisTransaction->item_sku = $transaction->Item->SKU;
                  $thisTransaction->item_title = $transaction->Item->Title;
                  $thisTransaction->qty_purchased = $transaction->QuantityPurchased;

                  $thisTransaction->sale_record_number = $transaction->ShippingDetails->SellingManagerSalesRecordNumber;
                  if(isset($transaction->ShippingDetails->ShipmentTrackingDetails)){
                    $thisTransaction->tracking_number = $transaction->ShippingDetails->ShipmentTrackingDetails['0']->ShipmentTrackingNumber;
                  }
                  if(isset($transaction->ShippingDetails->ShipmentTrackingDetails)){
                    $thisTransaction->shipping_carrier = $transaction->ShippingDetails->ShipmentTrackingDetails['0']->ShippingCarrierUsed;
                  }


                  $thisTransaction->transaction_price = $transaction->TransactionPrice->value;
                  if(isset($transaction->Variation->VariationTitle)){
                    $thisTransaction->variation = $transaction->Variation->VariationTitle;
                  }

                  if(!$thisTransaction->save()){
                    //$result['savingError'][]=$thisTransaction->errors." Buyer ID: ".$thisOrder->BuyerUserID;
                    $result['savingError'][]=$thisTransaction->errors;
                  }

                }
              }

            }//end foreach

          }else{
            $result['status']='error';
            $result['message']='Errors:'."<br>";
            foreach ($orders['Error'] as $error) {
              $result['message'].=$error."<br>";
            }
          }
          return $result;
        }else{
          //echo Json::encode("NOT AJAX!Denied");
          return false;
        }
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
          $ebayTimeNow = $ebayOrder->ebayOfficialTime();
          $createdFrom = $ebayTimeNow->sub(new \DateInterval('P2D'));//2 days before ebay time now
          $orderLog = new OrderLog();
          $orderLog->ebay_id = $post['ebayID'];
          //$orderLog->status = OrderLog::STATUS_DOWNLOAD_INIT;
          $orderLog->create_from = $createdFrom->format('Y-m-d H:i:s');
          $orderLog->create_to = $ebayTimeNow->format('Y-m-d H:i:s');
        }
        $result = $ebayOrderApi->getOrdersByTime(new \DateTime($orderLog->create_from), new \DateTime($orderLog->create_to),$post['pageNum']);
        if(isset($result['Error'])){
          return $result;
        }else{
          if(!$result['moreOrders']){//no more orders
            $orderLog->status = OrderLog::STATUS_DOWNLOAD_DONE;
            if($orderLog->save()){

            }else{
              $result['Error'][]='Order Log failed to save';
              foreach ($orderLog->errors as $errorArray) {
                foreach ($errorArray as $error) {
                  $result['Error'][] = $error;
                }
              }
            }
          }else{//more pages to download
            
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

        $ebayOrderApi = new EbayOrder($post['ebayID']);
        $result = $ebayOrderApi->getOrdersByID($notPaidOrders);
        return $result;
      }

    }
    public function actionPreFetch()
    {
      if(Yii::$app->request->isAjax){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post=Yii::$app->request->post();
        $result = [];
        $ebayOrder = new EbayOrder($post['ebayID']);

        $ebayTime = $ebayOrder->ebayOfficialTime();
        $orderLog=$this->findModel($post['ebayID']);
        //return $orderLog;
        if(isset($orderLog)){

          if($orderLog->status===OrderLog::STATUS_PRE_FETCH){
            $createFrom=new \DateTime($orderLog->create_from);
          }else{
            $createFrom=new \DateTime($orderLog->create_to);
          }
          $preOrderInfo=$ebayOrder->getPreFetchInfo($createFrom,$ebayTime);//this createTo will be the new createFrom time
        }else{//first fetch start from 1 day before now
          $orderLog = new OrderLog();
          $orderLog->ebay_id = $post['ebayID'];
          $createFrom = clone $ebayTime;
          $preOrderInfo=$ebayOrder->getPreFetchInfo($createFrom->sub(new \DateInterval('P2D')),$ebayTime);
          //$result['preOrder']=$ebayTime->sub(new \DateInterval('P1D'))->format('Y-m-d H:i:s').'++'.$createTo->format('Y-m-d H:i:s');
        }
        if(isset($preOrderInfo['Error'])){
          $result['status']='error';
          $result['message']='Follow errors:'."<br>";
          foreach ($preOrderInfo['Error'] as $error) {
            $result['message'].=$error."<br>";
          }
        }else{
          $result['status']='success';
          $result['message']='Order Pre Fetch Success!';

          $orderLog->create_from =$createFrom->format('Y-m-d H:i:s');
          $orderLog->status = OrderLog::STATUS_PRE_FETCH;
          $orderLog->order_qty = $preOrderInfo['preOrderData'];
          $orderLog->create_to =$ebayTime->format('Y-m-d H:i:s');
          $orderLog->complete_at =date('Y-m-d H:i:s',time());
          //$orderLog->create_to =$ebayTime;
          if($orderLog->save()){
            $result['message'] .= "<br>".'Order Log Saved!';
            $result['fetchQtyCount'] = $preOrderInfo['preOrderData'];
            $result['totalPages'] = $preOrderInfo['totalPages'];
          }else{
            $result['status']='error';
            $result['message'].="<br>"."Order Log Failed to Save";
            $result['savingError'][]=$orderLog->errors;
          }
        }
      return $result;
      }else{
          //echo Json::encode("NOT AJAX!Denied");
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
