<?php

namespace frontend\models\packagingpost;

/**
 * This is the ActiveQuery class for [[PackagingPost]].
 *
 * @see PackagingPost
 */
class PackagingPostQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return PackagingPost[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PackagingPost|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

     /**
     * 拿到属于用户的所有，返回数组
     * @param  [type] $userID [description]
     * @param  [type] $db     [description]
     * @return [type]         [description]
     */
    public function allOfUser($userID,$db = null){
        $this->where(['user_id'=>$userID]);
        $this->orderBy('price ASC');
        $this->asArray();
        return parent::all($db);
    }
}