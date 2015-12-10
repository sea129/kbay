<?php

namespace frontend\models\products;

use Yii;

/**
 * This is the model class for table "product_relation".
 *
 * @property integer $main
 * @property integer $sub
 *
 * @property Product $main0
 * @property Product $sub0
 */
class ProductRelation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_relation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['main', 'sub'], 'required'],
            [['main', 'sub'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'main' => Yii::t('app/product', 'Main'),
            'sub' => Yii::t('app/product', 'Sub'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMain0()
    {
        return $this->hasOne(Product::className(), ['id' => 'main']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSub0()
    {
        return $this->hasOne(Product::className(), ['id' => 'sub']);
    }

    /**
     * @inheritdoc
     * @return ProductRelationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductRelationQuery(get_called_class());
    }
}
