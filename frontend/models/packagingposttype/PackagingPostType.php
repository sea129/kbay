<?php

namespace frontend\models\packagingposttype;

use Yii;

/**
 * This is the model class for table "packaging_post_type".
 *
 * @property string $type
 * @property string $description
 *
 * @property PackagingPost[] $packagingPosts
 */
class PackagingPostType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'packaging_post_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['type'], 'string', 'max' => 64],
            [['description'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type' => Yii::t('app/packagingposttype', 'Type'),
            'description' => Yii::t('app/packagingposttype', 'Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackagingPosts()
    {
        return $this->hasMany(PackagingPost::className(), ['type' => 'type']);
    }

    /**
     * @inheritdoc
     * @return PackagingPostTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PackagingPostTypeQuery(get_called_class());
    }
}
