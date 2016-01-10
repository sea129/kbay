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

    public function actionFetchIndex(){
      $query = (new \yii\db\Query())
            ->select(['ebay_account.seller_id','ebay_account.id AS ebay_id','order_log.order_qty','order_log.create_from','order_log.create_to','order_log.complete_at','order_log.status'])
            ->from('ebay_account')
            ->leftJoin('order_log','ebay_account.id = order_log.ebay_id')
            ->where(['ebay_account.user_id'=>Yii::$app->user->id])
            ;
      $dataProvider = new ActiveDataProvider([
          'query' => $query,
      ]);
      return $this->render('fetch_index',[
        'dataProvider' => $dataProvider,
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

    public function actionMainFetch(){
        if(Yii::$app->request->isAjax){
          Yii::$app->response->format = Response::FORMAT_JSON;
          $post=Yii::$app->request->post();
          $result = [];
          $ebayOrder = new EbayOrder($post['ebayID']);
          $orderLog = OrderLog::findOne($post['ebayID']);
          $createFrom=new \DateTime($orderLog->create_from);
          $createTo=new \DateTime($orderLog->create_to);
          $orders = $ebayOrder->mainFetch($createFrom,$createTo,$post['pageNumber']);
          if(!isset($orders['Error'])){
            $result['status']='success';
            $result['message']='Connection Success';
            foreach ($orders['orders'] as $order) {
              $result['buyerID'][]=$order->BuyerUserID;
            }
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
          $createFrom = clone $ebayTime;
          $preOrderInfo=$ebayOrder->getPreFetchInfo($createFrom->sub(new \DateInterval('P1D')),$ebayTime);
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
          if(!isset($orderLog)){
            $orderLog = new OrderLog();
            $orderLog->ebay_id = $post['ebayID'];
            $orderLog->status = OrderLog::STATUS_PRE_FETCH;
            $orderLog->create_from =$createFrom->format('Y-m-d H:i:s');
          }
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
