<?php

namespace frontend\models\listingtemplate;

/**
 * This is the ActiveQuery class for [[ListingTemplate]].
 *
 * @see ListingTemplate
 */
class ListingTemplateQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return ListingTemplate[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ListingTemplate|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}