<?php

namespace frontend\models\supplier;

use Yii;

/**
 * This is the model class for table "supplier".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string $email
 * @property string $comment
 * @property integer $user_id
 *
 * @property Product[] $products
 * @property User $user
 */
class Supplier extends \frontend\models\base\MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'supplier';
    }

    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'user_id'], 'required'],
            [['comment'], 'string'],
            [['user_id'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['address'], 'string', 'max' => 256],
            [['phone', 'email'], 'string', 'max' => 32],
            [['name', 'user_id'], 'unique', 'targetAttribute' => ['name', 'user_id'], 'message' => 'The combination of Name and User ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/supplier', 'ID'),
            'name' => Yii::t('app/supplier', 'Name'),
            'address' => Yii::t('app/supplier', 'Address'),
            'phone' => Yii::t('app/supplier', 'Phone'),
            'email' => Yii::t('app/supplier', 'Email'),
            'comment' => Yii::t('app/supplier', 'Comment'),
            'user_id' => Yii::t('app/supplier', 'User ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['supplier_id' => 'id']);
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
     * @return SupplierQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SupplierQuery(get_called_class());
    }
}
