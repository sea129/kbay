<?php

namespace frontend\models\orders;

/**
 * This is the ActiveQuery class for [[OrderLog]].
 *
 * @see OrderLog
 */
class OrderLogQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return OrderLog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return OrderLog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}