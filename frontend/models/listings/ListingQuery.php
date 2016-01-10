<?php

namespace frontend\models\listings;

/**
 * This is the ActiveQuery class for [[Listing]].
 *
 * @see Listing
 */
class ListingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Listing[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Listing|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}