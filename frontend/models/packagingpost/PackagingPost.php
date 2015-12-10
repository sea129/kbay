<?php

namespace frontend\models\packagingpost;

use Yii;

/**
 * This is the model class for table "packaging_post".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property integer $weight_offset
 * @property string $price
 * @property string $material
 * @property string $description
 * @property string $type
 *
 * @property PackagingPostType $type0
 * @property User $user
 * @property Product[] $products
 */
class PackagingPost extends \frontend\models\base\MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'packaging_post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'name', 'weight_offset', 'price', 'material', 'type'], 'required'],
            [['user_id', 'weight_offset'], 'integer'],
            [['price'], 'number'],
            [['description'], 'string'],
            [['name', 'material', 'type'], 'string', 'max' => 64],
            [['user_id', 'name'], 'unique', 'targetAttribute' => ['user_id', 'name'], 'message' => 'The combination of User ID and Name has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/packagingpost', 'ID'),
            'user_id' => Yii::t('app/packagingpost', 'User ID'),
            'name' => Yii::t('app/packagingpost', 'Name'),
            'weight_offset' => Yii::t('app/packagingpost', 'Weight Offset'),
            'price' => Yii::t('app/packagingpost', 'Price'),
            'material' => Yii::t('app/packagingpost', 'Material'),
            'description' => Yii::t('app/packagingpost', 'Description'),
            'type' => Yii::t('app/packagingpost', 'Type'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType0()
    {
        return $this->hasOne(PackagingPostType::className(), ['type' => 'type']);
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
        return $this->hasMany(Product::className(), ['packaging_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return PackagingPostQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PackagingPostQuery(get_called_class());
    }

    public function getAllTypes()
    {
        $objArray = \frontend\models\packagingposttype\PackagingPostType::find()->asArray()->all();
        
        $dropdownArray = [];
        foreach ($objArray as $value) {
            $dropdownArray[$value['type']] = $value['type'];
        }

        return $dropdownArray;
    }
}
