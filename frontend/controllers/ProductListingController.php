<?php

namespace frontend\controllers;

use frontend\models\ebayaccounts\EbayAccount;

class ProductListingController extends \yii\web\Controller
{
    /**
     * 遍历所有用户ebay账户的listing，拿到itemID,储存到productebay表中
     * @return [type] [description]
     */
    public function actionSynListingID()
    {
    	
    }

    public function actionIndex(){
    	return $this->render('index');
    }

}
