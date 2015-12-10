<?php

namespace frontend\models\ebayaccounts;

use Yii;
/**
 * This is the ActiveQuery class for [[EbayAccount]].
 *
 * @see EbayAccount
 */
class EbayAccountQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return EbayAccount[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return EbayAccount|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * 拿到属于用户的所有ebay账号，返回数组
     * @param  [type] $userID [description]
     * @param  [type] $db     [description]
     * @return [type]         [description]
     */
    public function allOfUser($userID,$db = null){
        $this->where(['user_id'=>$userID]);
        $this->asArray();
        return parent::all($db);
    }
    public function allOfUserObj($userID,$db = null){
        $this->where(['user_id'=>$userID]);
        return parent::all($db);
    }
    
    public function allOfCurrentUser($db = null){

        $this->where(['user_id'=>Yii::$app->user->id]);
        return parent::all($db);
    }


}