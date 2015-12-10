<?php 

namespace app\modules\rbacm\models;

use yii\rbac\Item;

class Role extends AuthItem
{
	public function __construct($config = [])
    {
        $this->type= Item::TYPE_ROLE;
        parent::__construct($config);
    }

}

 ?>