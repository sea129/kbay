<?php

namespace frontend\models\orders;

/**
 * This is the ActiveQuery class for [[EbayTransaction]].
 *
 * @see EbayTransaction
 */
class EbayTransactionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return EbayTransaction[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return EbayTransaction|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}