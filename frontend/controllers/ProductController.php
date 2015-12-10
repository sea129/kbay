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
                    'delete' => ['get'],
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
        //$listings = (new EbayListing())->getListingInAllEbay($product->sku);
        if(Yii::$app->user->can('ebaycontrol',['userID'=>$product->user_id])){
           return $this->render('view', [
                'model' => $product,
                //'listings' => $listings,
            ]);
        }else{
            throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }

    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();
        $model->qty_per_order = 1;
        if ($model->load(Yii::$app->request->post())) {
            $oldSKU = explode('-',$model->sku);
            $oldSKU['1'] .= $model->user_id.$model->id;
            $model->sku = implode('-',$oldSKU);
            $model->main_image = $this->moveTempImage($model);
            if($model->save())
            {
                return $this->redirect(['view', 'id' => $model->id]);

            }else{
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

        } else {
            if(!Yii::$app->request->isAjax && $tempImage = Yii::$app->session->get('tempProductImage')){//not pjax
                //删除temp product image
                unlink($tempImage);
                Yii::$app->session->remove('tempProductImage');
            }
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    /**
     * 把temp image移动到product image里并改名
     * @param  [type] $oldName [description]
     * @param  [type] $sku     [description]
     * @return [type]          [description]
     */
    private function moveTempImage($model){
        if($tempImage = Yii::$app->session->get('tempProductImage')){
            if(rename($tempImage,Yii::$app->params['privateImagePath'].'product-images/'.Yii::$app->user->id.'/'.$model->sku.'.'.pathinfo($tempImage, PATHINFO_EXTENSION))){
                if(Yii::$app->session->remove('tempProductImage')){
                    return $model->sku.'.'.pathinfo($tempImage, PATHINFO_EXTENSION);
                }else{
                    return false;
                }

            }else{
                return false;
            }
        }else{
            return $model->main_image?$model->main_image:false;
        }
    }

    public function actionAddBatchProduct($mainID){
        $model = new BatchProduct($mainID);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $relation = new ProductRelation();
            $relation->main = $mainID;
            $relation->sub = $model->id;
            if($relation->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                 throw new NotFoundHttpException('Error Saving Product');
            }

        }
        return $this->render('add_batch',[
            'model' => $model,
            'mainID' =>$mainID,

        ]);

    }

    public function actionUpdateBatchProduct($id)
    {
        $relation = ProductRelation::find()->where(['sub'=>$id])->one();
        $mainID = $relation->main;
        $model= new BatchProduct($mainID);
        $model = $model->findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }else{
            return $this->render('update_batch',[
                'model' => $model,
                'mainID' =>$mainID,
            ]);
        }
    }




    public function actionValidateForm($id=null,$mainID=null)
    {

        if($id){//update
            if($mainID){//update a batch product
                $model = BatchProduct::findOne($id);
            }else{//product
                $model = $this->findModel($id);
            }

        }else{//create
            if($mainID){
                $model = new BatchProduct($mainID);
            }else{
                $model = new Product();
            }
        }
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }else{
            var_dump($batch);
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
        if(Yii::$app->user->can('ebaycontrol',['userID'=>$model->user_id])){
            $mainImage = $model->main_image;
            if ($model->load(Yii::$app->request->post())){
                $model->main_image = $mainImage;
                $model->main_image = $this->moveTempImage($model);
                // if(!$model->validate()){
                //   var_dump($model->errors);exit();
                // }
                if($model->save())
                {
                    return $this->redirect(['view', 'id' => $model->id]);
                }else{
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }

            } else {
                if(!Yii::$app->request->isAjax && $tempImage = Yii::$app->session->get('tempProductImage')){//not pjax
                    //删除temp product image
                    unlink($tempImage);
                    Yii::$app->session->remove('tempProductImage');
                }
                return $this->render('update', [
                    'model' => $model,
                ]);
            }

        }else{
            throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
        if (($model = Product::findOne($id)) !== null) {
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
        //var_dump(Url::base(true));exit();
        // $opts = array('http' => array('header'=> 'Cookie: ' . $_SERVER['HTTP_COOKIE']."\r\n"));
        // $context = stream_context_create($opts);
        // $code = file_get_contents(Url::base(true).Url::to(['/product/preview-desc','id'=>$id,'ebayID'=>$ebayID]),false,$context);
        //$code = file_get_contents(Url::base(true).Url::to(['/product/preview-desc','id'=>$id,'ebayID'=>$ebayID]));

        return $this->render('template_code',[
                'code'=>$this->actionPreviewDesc($id, $ebayID),
            ]);
    }

    public function actionFindPackaging()
    {
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $post = Yii::$app->request->post();
            $model = new \frontend\models\packagingpost\PackagingPost;
            if($post['track']==1){
                $where = ['=','type','track parcel'];
            }else{
                $where = ['<>','type','track parcel'];
            }
            return $model->find()
                ->where($where)
                ->andWhere(['>=','weight_offset',$post['weight']])
                ->asArray()
                ->indexBy('id')
                ->all();

        }


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
     * 上传图片,处理上传产品主图片的动作
     * @return [type] [description]
     */
    public function actionAjaxUpload(){
        if(Yii::$app->request->isAjax){
            $model = new Product();
            $image = $model->uploadImage();
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($image!==false){
                $folder = Yii::$app->params['privateImagePath'].'product-images/'.Yii::$app->user->id;
                //return $folder;
                if(!is_dir($folder)){
                    mkdir($folder,0755,true);
                }
                if($image->saveAs($folder. '/temp/temp.'.$image->extension)){
                    //在session里保存上传图片,主要目的是保存后缀
                    $session = Yii::$app->session;
                    $session['tempProductImage'] = $folder.'/temp/temp.'.$image->extension;

                    return true;
                }
            }
        }
    }

    /**
     * read uploaded image and output it in create and update forms
     * @return [type] [description]
     */
    public function actionReadImage($imageName = null){
        if($imageName!=null){
            $tempImage = Yii::$app->params['privateImagePath'].'product-images/'.Yii::$app->user->id.'/'.$imageName;
            $response = Yii::$app->getResponse();
            $response->headers->set('Content-Type', 'image/jpeg');
            $response->format = Response::FORMAT_RAW;
            if ( !is_resource($response->stream = fopen($tempImage, 'r')) ) {
               throw new \yii\web\ServerErrorHttpException('file access failed: permission deny');
            }
            return $response->send();
        }else{
           $session = Yii::$app->session;
            if(!isset($session['tempProductImage'])){
                throw new NotFoundHttpException('The requested page does not exist.');
            }else{
                $response = Yii::$app->getResponse();
                $response->headers->set('Content-Type', 'image/jpeg');
                $response->format = Response::FORMAT_RAW;


                if ( !is_resource($response->stream = fopen($session['tempProductImage'], 'r')) ) {
                   throw new \yii\web\ServerErrorHttpException('file access failed: permission deny');
                }
                return $response->send();
            }
        }
    }

    // public function actionUploadListImage(){
    //     if(Yii::$app->request->isAjax){
    //         Yii::$app->response->format = Response::FORMAT_JSON;
    //         $product = $this->findModel(Yii::$app->request->post('productID'));
    //         if(Yii::$app->user->can('ebaycontrol',['userID'=>$product->user_id])){
    //             $product->scenario = 'upTmpLstImage';
    //             $product->listingTmpImage = UploadedFile::getInstanceByName('pimages[0]');
    //             if($product->uploadTmpLstImg(Yii::$app->request->post('ebaySeller'))){
    //                 return true;
    //             }
    //         }else{
    //             throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
    //         }
    //
    //         /*$product->ftpUpLstImages($file);
    //
    //         $ftp = new \yii2mod\ftp\FtpClient();
    //
    //         $ftp->connect('ftp.ebayimages.x10host.com');
    //         $ftp->login('admin@ebayimages.x10host.com','Sea85129');
    //         //return var_dump(Yii::$app->request->post());
    //         //return '/pimages/'.Yii::$app->request->post('sellerID').'/'.Yii::$app->request->post('productSKU');
    //         $imageDir = '/pimages/'.Yii::$app->request->post('sellerID').'/'.Yii::$app->request->post('productSKU');
    //         if($ftp->isDir($imageDir)){
    //
    //         }else{
    //             $ftp->mkdir('/pimages/'.Yii::$app->request->post('sellerID').'/'.Yii::$app->request->post('productSKU'));
    //         }*/
    //
    //         //return UploadedFile::getInstanceByName('pimages[0]');
    //     }
    // }
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
