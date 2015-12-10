<?php

namespace frontend\models\products;

/**
 * This is the ActiveQuery class for [[ProductRelation]].
 *
 * @see ProductRelation
 */
class ProductRelationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return ProductRelation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ProductRelation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}