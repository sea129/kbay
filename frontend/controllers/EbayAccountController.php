<?php

namespace frontend\controllers;

use Yii;
use frontend\models\ebayaccounts\EbayAccount;
use frontend\models\ebayaccounts\EbayAccountSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use frontend\components\ebayapi\EbayApi;
use frontend\components\ebayapi\EbayListing;

use frontend\models\productebaylisting\ProductEbayListing;
/**
 * EbayAccountController implements the CRUD actions for EbayAccount model.
 */
class EbayAccountController extends Controller
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
     * Lists all EbayAccount models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EbayAccountSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EbayAccount model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $ebayAcc = $this->findModel($id);
        $synListingInfo = ProductEbayListing::find()->allOfEbay($id,$db = null);
        return $this->render('view', [
            'model' => $ebayAcc,
            'synListingInfo' => $synListingInfo,
        ]);
    }

    /**
     * Creates a new EbayAccount model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EbayAccount();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing EbayAccount model.
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
     * Deletes an existing EbayAccount model.
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
     * Finds the EbayAccount model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EbayAccount the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EbayAccount::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 拿到ebay token,存到数据库
     * @return [type] [description]
     */
    public function actionGetToken($id)
    {
        $session = Yii::$app->session;
        if(!$session->isActive){
            $session->open();
        }
        $theID=$session->get('ebSession');

        $ebayAcc = EbayAccount::findOne($id);

        $ebayApi = new EbayApi();
        if($theID===null){
            
            $sesId = $ebayApi->getSessionID();
            if($sesId!=false){
                $session->set('ebSession',$sesId['sesId']);
                return $this->redirect($sesId['loginURL'].urlencode($sesId['sesId']));
            }else{
                throw new NotFoundHttpException('fail to get ebay session ID');
            }
        }else{
            $token = $ebayApi->getToken($theID);
            if($token==false){
                throw new NotFoundHttpException('fail to get ebay token');
            }else{

                $ebayAcc->token = $token['0'];
                $ebayAcc->token_expiration = strtotime($token['1']);
                $ebayAcc->save();
                return $this->render('view', [
                    'model' => $ebayAcc,
                ]);
            }
        }
    }

    public function actionClearSession()
    {
        $session = Yii::$app->session;
        if(!$session->isActive){
            $session->open();
        }
        $session->remove('ebSession');
        return 'removed';
    }


    public function actionSyncListing($id)
    {
        $request = Yii::$app->request;
        if($request->isAjax){
            $ebayListing = new EbayListing($id);
            $syncResult = $ebayListing->syncListings(true);
            
            echo Json::encode($syncResult);
        }else{
            echo Json::encode("NOT AJAX!Denied");
        }
    }

}
