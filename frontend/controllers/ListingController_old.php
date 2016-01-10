<?php

namespace frontend\controllers;

use Yii;
use frontend\models\productebaylisting\ProductEbayListing;
use frontend\models\productebaylisting\SearchListing;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use frontend\models\ebayaccounts\EbayAccountSearch;
use frontend\models\ebayaccounts\EbayAccount;
/**
 * ListingController implements the CRUD actions for ProductEbayListing model.
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

    public function actionSync()
    {
      $query = (new \yii\db\Query())
            ->select(['ebay_account.seller_id','ebay_account.id AS ebay_id','MAX(product_ebay_listing.updated_at) AS Lastest_Updated_Time','COUNT(product_ebay_listing.ebay_account_id) AS Number_of_Listings'])
            ->from('ebay_account')
            ->innerJoin('product_ebay_listing','ebay_account.id = product_ebay_listing.ebay_account_id')
            ->where(['ebay_account.user_id'=>Yii::$app->user->id])
            ->groupBy(['product_ebay_listing.ebay_account_id']);
      $dataProvider = new ActiveDataProvider([
          'query' => $query,
      ]);
      return $this->render('sync',[
        'dataProvider' => $dataProvider,
      ]);
    }
    /**
     * Lists all ProductEbayListing models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchListing();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProductEbayListing model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ProductEbayListing model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProductEbayListing();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->item_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ProductEbayListing model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->item_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ProductEbayListing model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ProductEbayListing model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ProductEbayListing the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProductEbayListing::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
