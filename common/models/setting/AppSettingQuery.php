<?php

namespace common\models\setting;

/**
 * This is the ActiveQuery class for [[AppSetting]].
 *
 * @see AppSetting
 */
class AppSettingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return AppSetting[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AppSetting|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}