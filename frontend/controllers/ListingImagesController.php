<?php

namespace frontend\controllers;

use Yii;
use frontend\models\listingimages\ListingImages;
use frontend\models\listingimages\SearchListingImages;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
/**
 * CategoryController implements the CRUD actions for Category model.
 */
class ListingImagesController extends Controller
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

    public function actionCreate($productID,$ebayID,$imagesJson){

    }

    protected function findModel($product_id, $ebay_account_id)
    {
        if (($model = ListingImages::findOne(['product_id' => $product_id, 'ebay_account_id' => $ebay_account_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
