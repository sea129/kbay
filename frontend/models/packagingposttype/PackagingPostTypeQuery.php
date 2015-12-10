<?php

namespace frontend\models\packagingposttype;

/**
 * This is the ActiveQuery class for [[PackagingPostType]].
 *
 * @see PackagingPostType
 */
class PackagingPostTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return PackagingPostType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PackagingPostType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}