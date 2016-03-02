<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "user_setting".
 *
 * @property integer $user_id
 * @property integer $min_cost_tracking
 *
 * @property User $user
 */
class UserSetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'min_cost_tracking'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app/usersetting', 'User ID'),
            'min_cost_tracking' => Yii::t('app/usersetting', 'Min Cost Tracking'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
