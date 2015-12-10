<?php

namespace frontend\controllers;

use Yii;
use frontend\models\stocklocation\StockLocation;
use frontend\models\stocklocation\StockLocationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\web\Response;
use yii\helpers\Json;
/**
 * StockLocationController implements the CRUD actions for StockLocation model.
 */
class StockLocationController extends Controller
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
     * Lists all StockLocation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StockLocationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single StockLocation model.
     * @param string $code
     * @param integer $user_id
     * @return mixed
     */
    public function actionView($code, $user_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($code, $user_id),
        ]);
    }

    /**
     * Creates a new StockLocation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StockLocation();

        if(Yii::$app->request->isAjax){
            if($model->load(Yii::$app->request->post()) && $model->save()){
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode([true,$model->code]);
            }else{
                return Json::encode([false,$model->errors]);
            }
            
            
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'code' => $model->code, 'user_id' => $model->user_id]);
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
            
        }
    }

    public function actionValidateCreate()
    {
        $model = new StockLocation();
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    /**
     * Updates an existing StockLocation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $code
     * @param integer $user_id
     * @return mixed
     */
    public function actionUpdate($code, $user_id)
    {
        $model = $this->findModel($code, $user_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'code' => $model->code, 'user_id' => $model->user_id]);
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
            //return $this->redirect(['index']);
        }
    }

    /**
     * Deletes an existing StockLocation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $code
     * @param integer $user_id
     * @return mixed
     */
    public function actionDelete($code, $user_id)
    {
        $this->findModel($code, $user_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the StockLocation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $code
     * @param integer $user_id
     * @return StockLocation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($code, $user_id)
    {
        if (($model = StockLocation::findOne(['code' => $code, 'user_id' => $user_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
