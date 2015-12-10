<?php 

namespace app\modules\rbacm\controllers;

use Yii;
use yii\web\Controller;
use yii\base\Model;
use yii\widgets\ActiveForm;
use yii\web\Response;
use yii\rbac\Item;

use app\modules\rbacm\models\AuthItemSearch;
class ItemController extends Controller
{
	

	public function actionIndex()
	{
		$model = new AuthItemSearch(null,['type'=>$this->type,'scenario'=>'search']);

    	return $this->render('index', [
            'filterModel'  => $model,
            'dataProvider' => $model->search(\Yii::$app->request->get()),
        ]);
        
	}


	public function actionCreate()
    {
		$model = \Yii::createObject($this->modelClass,[null,]);
		$model->scenario = 'create';
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'name' => $model->name]);
        }else{
        	return $this->render('create', [
	            'model' => $model,
	        ]);
        }

        
    }

   	public function actionUpdate($name)
    {
        $item  = $this->getItem($name);
        $model = \Yii::createObject($this->modelClass,[$item,]);

        if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {

            return $this->redirect(['view', 'name' => $model->name]);
        }
        return $this->render('update', ['model' => $model,]);
    }

    public function actionUpdateChildren()
    {	
		$post=Yii::$app->getRequest()->post();
		$authManager = Yii::$app->getAuthManager();
		$childrenArray = $authManager->getChildren($post['name']);

		$children = array_keys($childrenArray);

		$item = $this->getItem($post['name']);

		if($post['children']!='no children')
		{
			foreach (array_diff($post['children'], $children) as $addItem) {
				if($authManager->getRole($addItem)===null){
					$authManager->addChild($item, $authManager->getPermission($addItem));
				}else{
					$authManager->addChild($item, $authManager->getRole($addItem));
				}
                
            }
            
            foreach (array_diff($children, $post['children']) as $delItem) {
                $authManager->removeChild($item, $childrenArray[$delItem]);
            }
		}else{
			$authManager->removeChildren($item);
		}
		
		return $this->redirect(['view','name'=>$post['name']]);
		//$authManager->addChild($item,);

    }

    public function actionView($name)
    {
    	$authManager = Yii::$app->getAuthManager();
    	$item = $this->getItem($name);
    	$model = \Yii::createObject($this->modelClass,[$item,]);

    	if($this->type===Item::TYPE_PERMISSION){
    		return $this->render('view', ['model'=>$model]);
    	}
    	

    	$avaliable = $assigned = [
            'Roles' => [],
            'Permissions' => [],
        ];
        $childrenArray = $authManager->getChildren($name);
        $children = array_keys($childrenArray);

        foreach ($authManager->getRoles() as $key => $role) {//$key is the auth item name
            /*if (in_array($key, $children)) {
                continue;
            }*/

            if($key!=$name&&!$authManager->hasChild($role,$item)){
            	$avaliable['Roles'][$key] = $key;
            }
            
        }
        foreach ($authManager->getPermissions() as $key => $role) {
            /*if (in_array($key, $children)) {
                continue;
            }*/
            $avaliable['Permissions'][$key] = $key;
        }
        foreach ($authManager->getChildren($name) as $key => $child) {
            if ($child->type == Item::TYPE_ROLE) {
                $assigned['Roles'][$key] = $key;
            } else {
                $assigned['Permissions'][$key] = $key;
            }
        }
        
        $allPermissions = new \yii\data\ArrayDataProvider([
        		'allModels' => $authManager->getPermissionsByRole($name),
        	]);


        /*$avaliable = array_filter($avaliable);
        $assigned = array_filter($assigned);*/
    	
    	return $this->render('view', ['model' => $model, 'avaliable' => $avaliable, 'assigned' => $assigned, 'allPermissions'=>$allPermissions]);
    }

    public function actionDelete($name)
    {
        $item = $this->getItem($name);
        \Yii::$app->authManager->remove($item);
        return $this->redirect(['index']);
    }

}


?>