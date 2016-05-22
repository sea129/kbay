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
use frontend\models\products\Product;
use frontend\models\listingimages\ListingImages;
use yii\web\Response;
use frontend\models\listings\ApiListingItem;

use frontend\components\ebayapi\EbayApi;
use frontend\components\ebayapi\EbayListing;
use frontend\components\ebayapi\ShoppingApi;
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

    public function actionAddFixedPriceListing()
    {
      if(Yii::$app->request->isAjax){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post=Yii::$app->request->post();
        $listingApi = new EbayListing($post['ebayID']);
        //$productController = new ProductController();
        $product = Product::findOne($post['productID']);
        $ebayAccount = EbayAccount::findOne($post['ebayID']);
        $lstImages = ListingImages::findOne(['product_id'=>$post['productID'],'ebay_account_id'=>$post['ebayID']]);
        $listingImages = ['http://i.imgur.com/roXpiR8.png'];
        if($lstImages){
          $listingImages = json_decode($lstImages->image_url);
        }

        //$description = Yii::$app->runAction("product/preview-desc",['id'=>$post['productID'],'ebayID'=>$post['ebayID']]);

        $listingItem = new ApiListingItem();
        $listingItem->title = $post['itemTitle'];
        $listingItem->qty = $post['qty'];
        $listingItem->price = $post['itemPrice'];

        $listingItem->bestOffer = $post['bestOffer'];

        if($post['freeShipping']!=true){
          $listingItem->shippingCost = $post['freeShipping'];
        }

        $listingItem->description = $this->renderFile(Yii::$app->params['listingTemplatePath'].$ebayAccount->listingTemplate->name.'.php',[
            'ebayAcc'=>$ebayAccount,
            'product'=>$product,
            'lstImages'=>$listingImages,
        ]);

        $listingItem->sku = $product->sku;
        $listingItem->location = $ebayAccount->item_location;
        $listingItem->picture = $listingImages;
        $listingItem->primaryCate = $post['ebayCatID'];
        $listingItem->paypal = $ebayAccount->paypal;
        $listingItem->shippingService = $post['shippingService'];

        //return var_dump($post['verify']);
        //if($post['verify']=="true"){
          $result=$listingApi->addFixedPriceListing($listingItem);
        //}else{
          //$result = $listingApi->verifyAddFixedPriceListing($listingItem);
        //}

        if(isset($result['itemID'])&&!isset($result['error'])){
          $newListing = new Listing();
          $newListing->item_id = $result['itemID'];
          $newListing->sku = $product->sku;
          $newListing->ebay_id = $post['ebayID'];
          $newListing->price = $post['itemPrice'];
          $newListing->title = $post['itemTitle'];
          $newListing->qty = $post['qty'];
          $newListing->sold_qty = 0;
          $newListing->sync_at = date('Y-m-d H:i:s',time());
          $newListing->user_id = Yii::$app->user->id;
          if($newListing->save()){
            $result['status'] = 'success';
            $result['message'] = "Listing ID: ".$newListing->item_id." created and saved";
          }else{
            $result['status'] = 'failed';
            $result['message'] = "Listing ID: ".$newListing->item_id." created,but not saved(sycned)";
          }
        }else{
          $result['status'] = 'failed';
          $result['message'] = 'Follow errors:'."<br>";
          foreach ($result['error'] as $error) {
            $result['message'].=$error."<br>";
          }
        }
        return $result;
      }
    }
    public function actionSearchSimilar()
    {
      if(Yii::$app->request->isAjax){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post=Yii::$app->request->post();
        $shoppingApi = new ShoppingApi();
        $item = $shoppingApi->getItem($post['itemId']);

        return $item;
      }
    }

    public function actionReviseOneItem()
    {
      if(Yii::$app->request->isAjax){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post=Yii::$app->request->post();
        $ebayListing = new EbayListing($post['ebayID']);
        $listing=Listing::find()->where(['item_id'=>$post['itemID']])->one();
        if($listing==null){
          return false;
        }
        $listingItem = new ApiListingItem();
        $listingItem->item_id = $listing->item_id;
        $listingItem->title = $post['title'];
        $listingItem->qty = $post['qty'];
        $listingItem->price = $post['price'];
        $listingItem->shippingCost = $post['shipping'];
        $listingItem->shippingExpressCost = $post['expressShipping'];
        $product = Product::findOne(['sku'=>$listing->sku]);
        $ebayAccount = EbayAccount::findOne($post['ebayID']);
        $lstImages = ListingImages::findOne(['product_id'=>$product->id,'ebay_account_id'=>$post['ebayID']]);
        $listingImages = ['http://i.imgur.com/roXpiR8.png'];
        if($lstImages){
          $listingImages = json_decode($lstImages->image_url);
        }

        $listingItem->description = $this->renderFile(Yii::$app->params['listingTemplatePath'].$ebayAccount->listingTemplate->name.'.php',[
            'ebayAcc'=>$ebayAccount,
            'product'=>$product,
            'lstImages'=>$listingImages,
        ]);

        $result=$ebayListing->reviseItem($listingItem);
        if(isset($result['itemID'])&&!isset($result['error'])){
          $result['status'] = 'success';
          $result['message'] = "Item: ".$result['itemID']." revised";
        }else{
          $result['status'] = 'failed';
          $result['message'] = "Item: ".$result['itemID']." NOT revised";
        }

        return $result;
      }else{
        return false;
      }
    }

    public function actionGetOneItemReviseInfo()
    {
      if(Yii::$app->request->isAjax){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post=Yii::$app->request->post();
        $ebayListing = new EbayListing($post['ebayID']);
        $item = $ebayListing->getOneItem($post['itemID']);
        // $listingItem = Listing::find()->where(['item_id'=>$post['itemID']])->one();
        // if($listingItem==null){
        //   $listingItem = new Listing();
        //   $listingItem->item_id = $post['itemID'];
        // }
        // $listingItem->sku = $item['sku'];
        // $listingItem->ebay_id = $post['ebayID'];
        // $listingItem->price = $item['price'];
        // $listingItem->title = $item['title'];
        // $listingItem->qty = $item['qty'];
        // $listingItem->sold_qty = $item['qtySold'];
        // $listingItem->sync_at = date('Y-m-d H:i:s',time());
        // $listingItem->user_id = Yii::$app->user->id;
        // if($listingItem->save()){
        //   return $item;
        // }else{
        //   return false;
        // }
        return $item;
      }else{
        return false;
      }
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
                $newListing = Listing::find()->where(['ebay_id'=>$post['ebayID'],'sku'=>$listing['sku']])->one();
                if($newListing!=null){
                  $newListing->delete();
                }
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
