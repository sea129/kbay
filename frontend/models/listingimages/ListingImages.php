<?php

namespace frontend\models\listingimages;

use Yii;

/**
 * This is the model class for table "listing_images".
 *
 * @property integer $product_id
 * @property integer $ebay_account_id
 * @property string $image_url
 *
 * @property EbayAccount $ebayAccount
 * @property Product $product
 */
class ListingImages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'listing_images';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'ebay_account_id', 'image_url'], 'required'],
            [['product_id', 'ebay_account_id'], 'integer'],
            [['image_url'], 'string'],
            [['product_id', 'ebay_account_id'], 'unique', 'targetAttribute' => ['product_id', 'ebay_account_id'], 'message' => 'The combination of Product and ebay account has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => Yii::t('app/listingimages', 'Product ID'),
            'ebay_account_id' => Yii::t('app/listingimages', 'Ebay Account ID'),
            'image_url' => Yii::t('app/listingimages', 'Image Url'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEbayAccount()
    {
        return $this->hasOne(EbayAccount::className(), ['id' => 'ebay_account_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @inheritdoc
     * @return ListingImagesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ListingImagesQuery(get_called_class());
    }

    
}
