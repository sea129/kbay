<?php

namespace frontend\models\orders;

use Yii;

/**
 * This is the model class for table "order_log".
 *
 * @property integer $ebay_id
 * @property integer $status
 * @property integer $order_qty
 * @property string $create_from
 * @property string $create_to
 * @property string $complete_at
 *
 * @property EbayAccount $ebay
 */
class OrderLog extends \yii\db\ActiveRecord
{
    const STATUS_PRE_FETCH = 1;
    const STATUS_DONE_FETCH = 2;
    //const STATUS_IN_PROGRESS_FETCH = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ebay_id', 'status', 'order_qty'], 'required'],
            [['ebay_id', 'status', 'order_qty'], 'integer'],
            [['create_from', 'create_to', 'complete_at'], 'safe'],
            ['ebay_id','unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ebay_id' => Yii::t('app/order', 'Ebay ID'),
            'status' => Yii::t('app/order', 'Status'),
            'order_qty' => Yii::t('app/order', 'Order Qty'),
            'create_from' => Yii::t('app/order', 'Create From'),
            'create_to' => Yii::t('app/order', 'Create To'),
            'complete_at' => Yii::t('app/order', 'Complete At'),
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
     * @inheritdoc
     * @return OrderLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrderLogQuery(get_called_class());
    }
}
