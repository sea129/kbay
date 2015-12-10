<?php

namespace frontend\models\ebayaccounts;

use Yii;
use frontend\models\listingtemplate\ListingTemplate;
/**
 * This is the model class for table "ebay_account".
 *
 * @property integer $id
 * @property string $seller_id
 * @property string $store_id
 * @property integer $user_id
 * @property string $shipping_info
 * @property string $warranty_info
 * @property string $payment_info
 * @property string $contact_info
 * @property integer $listing_template_id
 * @property string $token
 * @property string $token_expiration
 * @property string $email
 * @property string $listing_assets_url
 *
 * @property ListingTemplate $listingTemplate
 * @property User $user
 * @property ProductEbayListing[] $productEbayListings
 */
class EbayAccount extends \frontend\models\base\MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ebay_account';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['seller_id', 'user_id'], 'required'],
            [['user_id', 'listing_template_id'], 'integer'],
            [['shipping_info', 'warranty_info', 'payment_info', 'contact_info', 'token'], 'string'],
            [['token_expiration'], 'safe'],
            [['seller_id', 'store_id'], 'string', 'max' => 128],
            [['email'], 'string', 'max' => 64],
            [['listing_assets_url'], 'string', 'max' => 255],
            [['seller_id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/ebayaccount', 'ID'),
            'seller_id' => Yii::t('app/ebayaccount', 'Seller ID'),
            'store_id' => Yii::t('app/ebayaccount', 'Store ID'),
            'user_id' => Yii::t('app/ebayaccount', 'User ID'),
            'shipping_info' => Yii::t('app/ebayaccount', 'Shipping Info'),
            'warranty_info' => Yii::t('app/ebayaccount', 'Warranty Info'),
            'payment_info' => Yii::t('app/ebayaccount', 'Payment Info'),
            'contact_info' => Yii::t('app/ebayaccount', 'Contact Info'),
            'listing_template_id' => Yii::t('app/ebayaccount', 'Listing Template ID'),
            'token' => Yii::t('app/ebayaccount', 'Token'),
            'token_expiration' => Yii::t('app/ebayaccount', 'Token Expiration'),
            'email' => Yii::t('app/ebayaccount', 'Email'),
            'listing_assets_url' => Yii::t('app/ebayaccount', 'Listing Assets Url'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getListingTemplate()
    {
        return $this->hasOne(ListingTemplate::className(), ['id' => 'listing_template_id']);
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
    public function getProductEbayListings()
    {
        return $this->hasMany(ProductEbayListing::className(), ['ebay_account_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return EbayAccountQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EbayAccountQuery(get_called_class());
    }
}
