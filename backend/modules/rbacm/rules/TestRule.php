<?php 
namespace app\modules\rbacm\rules;

use yii\rbac\Rule;
/**
* 
*/
class TestRule extends Rule
{
	
	public $name = 'isTest';

	public function execute($user, $item, $params)
	{
		return true;
	}
}

 ?>