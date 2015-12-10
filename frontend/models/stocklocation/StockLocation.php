<?php

namespace frontend\models\stocklocation;

use Yii;

/**
 * This is the model class for table "stock_location".
 *
 * @property string $code
 * @property integer $user_id
 *
 * @property Product[] $products
 * @property User $user
 */
class StockLocation extends \frontend\models\base\MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stock_location';
    }

    /**
     * @inheritdoc
     */
    
    
    public function rules()
    {
        return [
            [['code', 'user_id'], 'required'],
            [['user_id'], 'integer'],
            [['code'], 'string', 'max' => 64],
            [['code', 'user_id'], 'unique', 'targetAttribute' => ['code', 'user_id'], 'message' => 'Location already exists']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'code' => Yii::t('app/stocklocation', 'Code'),
            'user_id' => Yii::t('app/stocklocation', 'User ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['stock_location' => 'code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return StockLocationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new StockLocationQuery(get_called_class());
    }
}
