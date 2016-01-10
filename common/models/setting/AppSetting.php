<?php

namespace common\models\setting;

use Yii;

/**
 * This is the model class for table "app_setting".
 *
 * @property string $name
 * @property integer $number_value
 * @property string $string_value
 * @property string $updated_at
 */
class AppSetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['number_value'], 'integer'],
            [['updated_at'], 'safe'],
            [['name', 'string_value'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app/setting', 'Name'),
            'number_value' => Yii::t('app/setting', 'Number Value'),
            'string_value' => Yii::t('app/setting', 'String Value'),
            'updated_at' => Yii::t('app/setting', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     * @return AppSettingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AppSettingQuery(get_called_class());
    }
}
