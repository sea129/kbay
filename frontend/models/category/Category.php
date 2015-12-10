<?php

namespace frontend\models\category;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property string $description
 * @property integer $user_id
 *
 * @property User $user
 * @property Product[] $products
 */
class Category extends \frontend\models\base\MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code', 'user_id'], 'required'],
            [['description'], 'string'],
            [['user_id'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['code'], 'string', 'max' => 10],
            ['code', 'match', 'pattern' => '/^[A-Z0-9]+$/','message' => Yii::t('app/category', 'capital letters and numbers only')],
            [['code', 'user_id'], 'unique', 'targetAttribute' => ['code', 'user_id'], 'message' => 'The combination of Code and User ID has already been taken.'],
            [['name', 'user_id'], 'unique', 'targetAttribute' => ['name', 'user_id'], 'message' => 'The combination of Name and User ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/category', 'ID'),
            'name' => Yii::t('app/category', 'Name'),
            'code' => Yii::t('app/category', 'Code'),
            'description' => Yii::t('app/category', 'Description'),
            'user_id' => Yii::t('app/category', 'User ID'),
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
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['category_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return CategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }
}
