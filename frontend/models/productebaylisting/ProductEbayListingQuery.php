<?php

namespace frontend\models\productebaylisting;

/**
 * This is the ActiveQuery class for [[ProductEbayListing]].
 *
 * @see ProductEbayListing
 */
class ProductEbayListingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return ProductEbayListing[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ProductEbayListing|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    public function allOfEbay($ebayID,$db = null)
    {
        //$this->select(['*','COUNT(*)']);
        $this->where(['ebay_account_id'=>$ebayID]);
        $this->orderBy('updated_at DESC'); //最新的在第一个
        //$this->asArray();
        
        return parent::all($db);
    }
}