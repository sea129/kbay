<?php

namespace frontend\models\listingimages;

/**
 * This is the ActiveQuery class for [[ListingImages]].
 *
 * @see ListingImages
 */
class ListingImagesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return ListingImages[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ListingImages|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function allOfProduct($productID,$db = null){
      $this->where(['product_id'=>$productID]);
      $this->indexBy('ebay_account_id');
      $this->asArray();
      return parent::all($db);
    }
}
