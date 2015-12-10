<?php

namespace frontend\models\supplier;

/**
 * This is the ActiveQuery class for [[Supplier]].
 *
 * @see Supplier
 */
class SupplierQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Supplier[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Supplier|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * 拿到属于用户的所有Suppliers，返回数组
     * @param  [type] $userID [description]
     * @param  [type] $db     [description]
     * @return [type]         [description]
     */
    public function allOfUser($userID,$db = null){
        $this->where(['user_id'=>$userID]);
        $this->asArray();
        return parent::all($db);
    }
}