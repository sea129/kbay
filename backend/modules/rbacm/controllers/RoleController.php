<?php 

namespace app\modules\rbacm\controllers;

use yii\rbac\Item;
use yii\rbac\Role;
use yii\web\NotFoundHttpException;

class RoleController extends ItemController
{
	protected $type = Item::TYPE_ROLE;

	protected $modelClass = 'app\modules\rbacm\models\Role';

	protected function getItem($name)
    {
        $role = \Yii::$app->authManager->getRole($name);

        if ($role instanceof Role) {
            return $role;
        }

        throw new NotFoundHttpException;
    }
}

?>