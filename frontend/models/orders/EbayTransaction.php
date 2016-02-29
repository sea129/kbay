<?php

namespace frontend\models\orders;

use Yii;
use frontend\models\products\Product;

/**
 * This is the model class for table "ebay_transaction".
 *
 * @property string $transaction_id
 * @property integer $ebay_order_id
 * @property string $buyer_email
 * @property string $created_date
 * @property string $final_value_fee
 * @property string $item_id
 * @property string $item_sku
 * @property string $item_title
 * @property string $paid_time
 * @property integer $qty_purchased
 * @property integer $status
 * @property string $shipped_time
 * @property integer $sale_record_number
 * @property string $tracking_number
 * @property string $shipping_carrier
 * @property string $transaction_price
 * @property integer $variation
 *
 * @property EbayOrder $ebayOrder
 */
class EbayTransaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ebay_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transaction_id', 'ebay_order_id', 'item_id', 'item_title', 'qty_purchased'], 'required'],
            [['ebay_order_id', 'item_id', 'qty_purchased', 'sale_record_number'], 'integer'],
            [['created_date'], 'safe'],
            [['final_value_fee', 'transaction_price'], 'number'],
            [['transaction_id'], 'string', 'max' => 32],
            [['buyer_email', 'item_sku', 'tracking_number', 'shipping_carrier'], 'string', 'max' => 64],
            [['item_title'], 'string', 'max' => 132],
            [['variation', 'image'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'transaction_id' => Yii::t('app/order', 'Transaction ID'),
            'ebay_order_id' => Yii::t('app/order', 'Ebay Order ID'),
            'buyer_email' => Yii::t('app/order', 'Buyer Email'),
            'created_date' => Yii::t('app/order', 'Created Date'),
            'final_value_fee' => Yii::t('app/order', 'Final Value Fee'),
            'item_id' => Yii::t('app/order', 'Item ID'),
            'item_sku' => Yii::t('app/order', 'Item Sku'),
            'item_title' => Yii::t('app/order', 'Item Title'),
            'qty_purchased' => Yii::t('app/order', 'Qty Purchased'),
            'sale_record_number' => Yii::t('app/order', 'Sale Record Number'),
            'tracking_number' => Yii::t('app/order', 'Tracking Number'),
            'shipping_carrier' => Yii::t('app/order', 'Shipping Carrier'),
            'transaction_price' => Yii::t('app/order', 'Transaction Price'),
            'variation' => Yii::t('app/order', 'Variation'),
            'image' => Yii::t('app/order', 'Image'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEbayOrder()
    {
        return $this->hasOne(EbayOrder::className(), ['id' => 'ebay_order_id']);
    }

    public function getProduct()
    {
      return $this->hasOne(Product::className(),['sku'=>'item_sku']);
    }
    /**
     * @inheritdoc
     * @return EbayTransactionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EbayTransactionQuery(get_called_class());
    }
}
