<?php 
namespace backend\modules\rbacm\rules;

use yii\rbac\Rule;
/**
* 
*/
class EbayUserRule extends Rule
{
	
	public $name = 'isOwn';

	public function execute($user, $item, $params)
	{
		return \Yii::$app->user->id===$params['userID']?true:false;
	}
}

 ?>