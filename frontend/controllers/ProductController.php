<?php

namespace frontend\controllers;

use Yii;
use frontend\models\products\Product;
use frontend\models\products\BatchProduct;
use frontend\models\products\ProductSearch;
use frontend\models\products\ProductRelation;
use frontend\models\listingimages\ListingImages;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\ebayaccounts\EbayAccount;
use frontend\components\ebayapi\EbayListing;

use yii\helpers\Json;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\UploadedFile;

use yii2mod\ftp\FtpClient;

use frontend\models\listings\Listing;
/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
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

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
      $product = $this->findModel($id);
      $listings = Listing::find()->where(['sku'=>$product->sku,'user_id'=>Yii::$app->user->id])->indexBy('ebay_id')->all();
      return $this->render('view', [
          'model' => $product,
          'listings'=>$listings,
      ]);

    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();
        $model->scenario = Product::SCENARIO_SINGLE;
        if($model->load(Yii::$app->request->post())) {
          $model->qty_per_order = 1;
          if($model->save()){
            return $this->redirect(['view', 'id' => $model->id]);
          }else {
            return $this->render('create', [
                'model' => $model,
            ]);
          }
        }else {
          $model->qty_per_order = 1;
          return $this->render('create', [
              'model' => $model,
          ]);
        }
    }

    /**
     * create a batch product base on one main product
     */
    public function actionAddBatchProduct($mainID){
        $model = new Product();
        $model->scenario = Product::SCENARIO_BATCH;
        $mainProduct = $this->findModel($mainID);
        $model->attributes = $mainProduct->getAttributes();
        $model->stock_qty = null;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $relation = new ProductRelation();
            $relation->main = $mainID;
            $relation->sub = $model->id;
            if($relation->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                 throw new NotFoundHttpException('Error Saving Product');
            }

        }else{
          $model->qty_per_order = 2;
          return $this->render('add_batch',[
              'model' => $model,
              'mainID' =>$mainID,

          ]);
        }


    }

    /**
     * update a batch product
     */
    public function actionUpdateBatchProduct($id)
    {
        $model=$this->findModel($id);
        $model->scenario = Product::SCENARIO_BATCH;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }else{
            return $this->render('update_batch',[
                'model' => $model,
            ]);
        }
    }

    public function actionValidateForm($id=null,$scenario=Product::SCENARIO_SINGLE)
    {
      if($id){
        $model=$this->findModel($id);
      }else{
        $model = new Product();
      }
      $model->scenario = $scenario;
      if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
          Yii::$app->response->format = Response::FORMAT_JSON;
          return ActiveForm::validate($model);
      }

    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = Product::SCENARIO_SINGLE;
        if ($model->load(Yii::$app->request->post())){
            $model->qty_per_order = 1;
            if($model->save())
            {
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                return $this->render('update', [
                    'model' => $model,
                ]);
            }

        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model=$this->findModel($id);
        if($model->delete()){
          return $this->redirect(['index']);
        }else {
            throw new NotFoundHttpException('Error when deleting the good');
        }
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null && Yii::$app->user->can('ebaycontrol',['userID'=>$model->user_id])) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionPreviewDesc($id, $ebayID){

        $product = $this->findModel($id);
        $ebayAcc = EbayAccount::findOne($ebayID);
        $lstImages = ListingImages::findOne(['product_id'=>$id,'ebay_account_id'=>$ebayID]);
        $listingImages = ['http://i.imgur.com/roXpiR8.png'];
        if($lstImages){
          $listingImages = json_decode($lstImages->image_url);
        }
        return $this->renderFile(Yii::$app->params['listingTemplatePath'].$ebayAcc->listingTemplate->name.'.php',[
            'ebayAcc'=>$ebayAcc,
            'product'=>$product,
            'lstImages'=>$listingImages,
        ]);
    }

    public function actionGenerateCode($id, $ebayID){
      return $this->render('template_code',[
                'code'=>$this->actionPreviewDesc($id, $ebayID),
            ]);
    }

    public function actionFindAllPackaging()
    {
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $post = Yii::$app->request->post();
            $model = new \frontend\models\packagingpost\PackagingPost;
            return $model->find()
                ->asArray()
                ->all();

        }
    }

    /**
     * 保存listing images
     */
    public function actionSaveLstImgInfo()
    {
      if(Yii::$app->request->isAjax){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $lstImages = ListingImages::findOne(['product_id'=>$post['productID'],'ebay_account_id'=>$post['ebayID']]);
        if($lstImages!=null){

          $lstImages->image_url = json_encode(array_merge(json_decode($lstImages->image_url),$post['imageArray']));
          if($lstImages->save()){
            return true;
          }else{
            return false;
          }
        }else{
          $lstImages = new ListingImages;
          $lstImages->product_id = $post['productID'];
          $lstImages->ebay_account_id = $post['ebayID'];
          $lstImages->image_url = json_encode($post['imageArray']);
          if($lstImages->save()){
            return true;
          }else{
            return false;
          }
        }


      }
    }


    public function actionUpdateLstImgInfo(){
      if(Yii::$app->request->isAjax){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        //return $post;
        $lstImages = ListingImages::findOne(['product_id'=>$post['productID'],'ebay_account_id'=>$post['ebayID']]);
        if (empty($post['listingImages'])&&$lstImages->delete()) {
          return true;
        }
        if($lstImages!=null){
          $lstImages->image_url = json_encode($post['listingImages']);
          if($lstImages->save()){
            return true;
          }else{
            return false;
          }
        }
      }
    }


}
