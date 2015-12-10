<?php

namespace frontend\models\base;

use Yii;


/**
*           
*/
class MyActiveRecord extends \yii\db\ActiveRecord 
{
    
    public function init()
    {
        parent::init();
        $this->user_id = Yii::$app->user->id;
    }
}

?>
