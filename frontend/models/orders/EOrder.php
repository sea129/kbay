<?php

namespace frontend\models\orders;

use Yii;
use frontend\models\ebayaccounts\EbayAccount;


/**
 * This is the model class for table "ebay_order".
 *
 * @property integer $id
 * @property integer $type
 * @property string $fetched_at
 * @property integer $status
 * @property integer $ebay_id
 * @property integer $user_id
 * @property string $ebay_order_id
 * @property string $ebay_seller_id
 * @property string $buyer_id
 * @property string $created_time
 * @property string $paid_time
 * @property string $recipient_name
 * @property string $recipient_phone
 * @property string $recipient_address1
 * @property string $recipient_address2
 * @property string $recipient_city
 * @property string $recipient_state
 * @property string $recipient_postcode
 * @property string $checkout_message
 *
 * @property EbayAccount $ebay
 * @property User $user
 * @property EbayTransaction[] $ebayTransactions
 */
class EOrder extends \frontend\models\base\MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ebay_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fetched_at', 'created_time', 'paid_time', 'shipped_time'], 'safe'],
            [['status', 'ebay_id', 'user_id', 'sale_record_number', 'label'], 'integer'],
            [['ebay_id', 'user_id', 'ebay_order_id', 'ebay_seller_id', 'buyer_id', 'recipient_name', 'recipient_address1', 'recipient_city', 'recipient_state', 'recipient_postcode'], 'required'],
            [['total'], 'number'],
            [['ebay_order_id', 'ebay_seller_id', 'recipient_name', 'recipient_city', 'recipient_state'], 'string', 'max' => 64],
            [['buyer_id', 'recipient_address1', 'recipient_address2'], 'string', 'max' => 128],
            [['recipient_phone', 'recipient_postcode'], 'string', 'max' => 32],
            [['checkout_message'], 'string', 'max' => 256],
            [['ebay_order_id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/order', 'ID'),
            'fetched_at' => Yii::t('app/order', 'Fetched At'),
            'status' => Yii::t('app/order', 'Status'),
            'ebay_id' => Yii::t('app/order', 'Ebay ID'),
            'user_id' => Yii::t('app/order', 'User ID'),
            'ebay_order_id' => Yii::t('app/order', 'Ebay Order ID'),
            'ebay_seller_id' => Yii::t('app/order', 'Ebay Seller ID'),
            'sale_record_number' => Yii::t('app/order', 'Sale Record Number'),
            'buyer_id' => Yii::t('app/order', 'Buyer ID'),
            'total' => Yii::t('app/order', 'Total'),
            'created_time' => Yii::t('app/order', 'Created Time'),
            'paid_time' => Yii::t('app/order', 'Paid Time'),
            'shipped_time' => Yii::t('app/order', 'Shipped Time'),
            'recipient_name' => Yii::t('app/order', 'Recipient Name'),
            'recipient_phone' => Yii::t('app/order', 'Recipient Phone'),
            'recipient_address1' => Yii::t('app/order', 'Recipient Address1'),
            'recipient_address2' => Yii::t('app/order', 'Recipient Address2'),
            'recipient_city' => Yii::t('app/order', 'Recipient City'),
            'recipient_state' => Yii::t('app/order', 'Recipient State'),
            'recipient_postcode' => Yii::t('app/order', 'Recipient Postcode'),
            'checkout_message' => Yii::t('app/order', 'Checkout Message'),
            'label' => Yii::t('app/order', 'Label'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEbay()
    {
        return $this->hasOne(EbayAccount::className(), ['id' => 'ebay_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEbayTransactions()
    {
        return $this->hasMany(EbayTransaction::className(), ['ebay_order_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return EbayOrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EbayOrderQuery(get_called_class());
    }

    public function getNonLabelCount(){
      
    }
    // public function getItemPicUrl($itemID){
    //   $ebayListing = new EbayListing($this->ebay_id);
    //   return $ebayListing->getItemPicUrl($itemID);
    // }
}
