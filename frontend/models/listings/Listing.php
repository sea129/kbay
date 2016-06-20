<?php

namespace frontend\models\listings;

use Yii;
use frontend\models\products\Product;
use frontend\models\ebayaccounts\EbayAccount;
/**
 * This is the model class for table "listing".
 *
 * @property integer $id
 * @property string $item_id
 * @property string $sku
 * @property integer $ebay_id
 * @property string $price
 * @property string $title
 * @property integer $qty
 * @property integer $sold_qty
 * @property string $sync_at
 *
 * @property Product $sku0
 * @property EbayAccount $ebay
 */
class Listing extends \frontend\models\base\MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'listing';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'sku', 'ebay_id', 'price', 'title', 'qty', 'sold_qty', 'user_id'], 'required'],
            [['ebay_id', 'qty', 'sold_qty', 'user_id'], 'integer'],
            [['price'], 'number'],
            [['sync_at'], 'safe'],
            [['item_id'], 'string', 'max' => 32],
            [['sku'], 'string', 'max' => 64],
            [['sku','item_id','title'],'trim'],
            [['title'], 'string', 'max' => 128],
            [['item_id'], 'unique'],
            [['sku', 'ebay_id'], 'unique', 'targetAttribute' => ['sku', 'ebay_id'], 'message' => 'The combination of Sku and Ebay ID has already been taken.'],
            [['sku'],'exist','targetClass'=>Product::className(),'targetAttribute'=>'sku','message' => Yii::t('app/product', 'The sku does not exist')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/listing', 'ID'),
            'item_id' => Yii::t('app/listing', 'Item ID'),
            'sku' => Yii::t('app/listing', 'Sku'),
            'ebay_id' => Yii::t('app/listing', 'Ebay ID'),
            'price' => Yii::t('app/listing', 'Price'),
            'title' => Yii::t('app/listing', 'Title'),
            'qty' => Yii::t('app/listing', 'Qty'),
            'sold_qty' => Yii::t('app/listing', 'Sold Qty'),
            'sync_at' => Yii::t('app/listing', 'Sync At'),
            'user_id' => Yii::t('app/listing', 'User ID'),
        ];
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
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['sku' => 'sku']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEbay()
    {
        return $this->hasOne(EbayAccount::className(), ['id' => 'ebay_id']);
    }

    /**
     * @inheritdoc
     * @return ListingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ListingQuery(get_called_class());
    }
}
