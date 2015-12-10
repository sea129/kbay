<?php 
namespace app\modules\rbacm\rules;

use yii\rbac\Rule;
/**
* 
*/

class TestRule2 extends Rule
{
	
	public $name = 'isTestb';

	public function execute($user, $item, $params)
	{
		return true;
	}
}
 ?>