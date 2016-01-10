<?php

namespace frontend\controllers;

use Yii;
use frontend\models\listings\Listing;
use frontend\models\listings\ListingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use frontend\models\ebayaccounts\EbayAccountSearch;
use frontend\models\ebayaccounts\EbayAccount;
use yii\web\Response;

use frontend\components\ebayapi\EbayApi;
use frontend\components\ebayapi\EbayListing;
/**
 * ListingController implements the CRUD actions for Listing model.
 */
class ListingController extends Controller
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
        ];
    }

    /**
     * 同步一页
     */
    public function actionSyncPage()
    {
      if(Yii::$app->request->isAjax){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post=Yii::$app->request->post();
        //return $result['listing']=var_dump($post['pageNumber']);
        $ebayListing = new EbayListing($post['ebayID']);
        $activeListing = $ebayListing->getListingPage((int)$post['pageNumber']);

        if(!isset($activeListing['Error'])){
          $result['status']='success';
          $result['message']='Connection Success';
          $result['listings_sync']=[];
          $result['listings_nosku']=[];
          foreach ($activeListing['listings'] as $listing) {
            if(isset($listing['sku'])){
              $newListing=Listing::find()->where(['item_id'=>$listing['item_id']])->one();
              if($newListing==null){
                $newListing = new Listing();
                $newListing->item_id = $listing['item_id'];
              }
              $newListing->sku = $listing['sku'];
              $newListing->ebay_id = $post['ebayID'];
              $newListing->price = $listing['price'];
              $newListing->title = $listing['title'];
              $newListing->qty = $listing['qty'];
              $newListing->sold_qty = $listing['sold_qty'];
              $newListing->sync_at = date('Y-m-d H:i:s',time());
              $newListing->user_id = Yii::$app->user->id;

              if($newListing->save()){
                $result['listings_sync'][]=$listing['item_id'];
              }else{
                $result['status']='error';
                $result['message'].="<br>".$listing['item_id']." failed to sync";
                $result['savingError'][]=$newListing->errors;
              }
            }else{//if no sku
              $newListing=Listing::find()->where(['item_id'=>$listing['item_id']])->one();
              if($newListing!==null){
                $newListing->delete();
              }
              $result['listings_nosku'][]=$listing['item_id'];
            }


          }
          //$result['listings'] = $activeListing['listings'];

        }else{
          $result['status']='error';
          $result['message']='Follow errors:'."<br>";
          foreach ($activeListing['Error'] as $error) {
            $result['message'].=$error."<br>";
          }
        }
        return $result;
      }else{
        return false;
      }
    }

    /**
     *
     */
    public function actionPreSync(){
      if(Yii::$app->request->isAjax){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post=Yii::$app->request->post();
        $ebayListing = new EbayListing($post['ebayID']);
        $preListingInfo = $ebayListing->getPreListingInfo();
        if(!isset($preListingInfo['Error'])){
          $result['status']='success';
          $result['message']='Connection Success';
          $result['data'] = $preListingInfo['data'];
          $result['totalPages'] = $preListingInfo['totalPages'];

        }else{
          $result['status']='error';
          $result['message']='Follow errors:'."<br>";
          foreach ($preListingInfo['Error'] as $error) {
            $result['message'].=$error."<br>";
          }
        }
        return $result;
        //$result['status']='error';

      }else{
          //echo Json::encode("NOT AJAX!Denied");
          return false;
      }
    }

    /**
     *
     */
    public function actionSync()
    {
      $query = (new \yii\db\Query())
            ->select(['ebay_account.seller_id','ebay_account.id AS ebay_id','MAX(listing.sync_at) AS Lastest_Sync_Time','COUNT(listing.ebay_id) AS Number_of_Listings'])
            ->from('ebay_account')
            ->leftJoin('listing','ebay_account.id = listing.ebay_id')
            ->where(['ebay_account.user_id'=>Yii::$app->user->id])
            ->groupBy(['ebay_account.id'])
            ;
      $dataProvider = new ActiveDataProvider([
          'query' => $query,
      ]);
      return $this->render('sync',[
        'dataProvider' => $dataProvider,
      ]);
    }

    /**
     * Lists all Listing models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ListingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Listing model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Listing model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Listing();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Listing model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Listing model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Listing model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Listing the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Listing::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
