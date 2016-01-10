<?php

namespace frontend\models\productebaylisting;

use Yii;

/**
 * This is the model class for table "product_ebay_listing".
 *
 * @property string $sku
 * @property integer $ebay_account_id
 * @property string $item_id
 * @property string $price
 * @property string $title
 * @property string $updated_at
 * @property integer $qty
 *
 * @property EbayAccount $ebayAccount
 * @property Product $sku0
 */
class ProductEbayListing extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_ebay_listing';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sku', 'ebay_account_id', 'item_id'], 'required'],
            [['ebay_account_id', 'qty', 'qty_sold'], 'integer'],
            [['price'], 'number'],
            [['updated_at'], 'safe'],
            [['sku'], 'string', 'max' => 64],
            [['item_id'], 'string', 'max' => 32],
            [['title'], 'string', 'max' => 128],
            [['item_id'], 'unique'],
            [['sku', 'ebay_account_id'], 'unique', 'targetAttribute' => ['sku', 'ebay_account_id'], 'message' => 'The combination of Sku and Ebay Account ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sku' => Yii::t('app/listing', 'Sku'),
            'ebay_account_id' => Yii::t('app/listing', 'Ebay Account ID'),
            'item_id' => Yii::t('app/listing', 'Item ID'),
            'price' => Yii::t('app/listing', 'Price'),
            'title' => Yii::t('app/listing', 'Title'),
            'updated_at' => Yii::t('app/listing', 'Updated At'),
            'qty' => Yii::t('app/listing', 'Qty'),
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
    public function getSku0()
    {
        return $this->hasOne(Product::className(), ['sku' => 'sku']);
    }

    /**
     * @inheritdoc
     * @return ProductEbayListingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductEbayListingQuery(get_called_class());
    }
}
