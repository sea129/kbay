<?php

namespace frontend\controllers;

use Yii;
use frontend\models\products\ProductRelation;
use frontend\models\products\ProductRelationtSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductRelationController implements the CRUD actions for ProductRelation model.
 */
class ProductRelationController extends Controller
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
     * Lists all ProductRelation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductRelationtSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProductRelation model.
     * @param integer $main
     * @param integer $sub
     * @return mixed
     */
    public function actionView($main, $sub)
    {
        return $this->render('view', [
            'model' => $this->findModel($main, $sub),
        ]);
    }

    /**
     * Creates a new ProductRelation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProductRelation();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'main' => $model->main, 'sub' => $model->sub]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ProductRelation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $main
     * @param integer $sub
     * @return mixed
     */
    public function actionUpdate($main, $sub)
    {
        $model = $this->findModel($main, $sub);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'main' => $model->main, 'sub' => $model->sub]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ProductRelation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $main
     * @param integer $sub
     * @return mixed
     */
    public function actionDelete($main, $sub)
    {
        $this->findModel($main, $sub)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ProductRelation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $main
     * @param integer $sub
     * @return ProductRelation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($main, $sub)
    {
        if (($model = ProductRelation::findOne(['main' => $main, 'sub' => $sub])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
