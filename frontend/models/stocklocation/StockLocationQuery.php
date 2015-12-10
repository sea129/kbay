<?php

namespace frontend\models\stocklocation;

/**
 * This is the ActiveQuery class for [[StockLocation]].
 *
 * @see StockLocation
 */
class StockLocationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return StockLocation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return StockLocation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * 拿到属于用户的所有仓库位置代码，返回数组
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