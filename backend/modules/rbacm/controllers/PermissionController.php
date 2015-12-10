<?php 

namespace app\modules\rbacm\controllers;

use yii\rbac\Item;
use yii\web\NotFoundHttpException;
use yii\rbac\Permission;

class PermissionController extends ItemController
{
	protected $type = Item::TYPE_PERMISSION;

	protected $modelClass = 'app\modules\rbacm\models\Permission';

	protected function getItem($name)
    {
        $permission = \Yii::$app->authManager->getPermission($name);

        if ($permission instanceof Permission) {
            return $permission;
        }

        throw new NotFoundHttpException;
    }
}

?>