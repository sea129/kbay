<?php 

namespace app\modules\rbacm\controllers;

use Yii;
use yii\web\Controller;

use yii\data\ArrayDataProvider;

use app\modules\rbacm\models\RuleSearch;
use app\modules\rbacm\models\Rule;
use app\modules\rbacm\rules\TestRule;

class RuleController extends Controller
{
    public function actionIndex() {
    	$authManager = Yii::$app->authManager;
        $dataProvider = new ArrayDataProvider([
            'allModels' => $authManager->getRules(),
        ]);
        return $this->render('index', [
                    'dataProvider' => $dataProvider,
        ]);
    }
    public function actionCreate(){
    	$model = new Rule(null);
    	if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }else{
        	return $this->render('create', [
	            'model' => $model,
	        ]);
        }
    }
    public function actionDelete($name){
    	$item = \Yii::$app->authManager->getRule($name);
    	if($item instanceof \yii\rbac\Rule){
    		\Yii::$app->authManager->remove($item);
    		return $this->redirect(['index']);
    	}else{
    		throw new \yii\web\NotFoundHttpException;
    	}
    }

    public function actionUpdate($name){
    	$item = \Yii::$app->authManager->getRule($name);
    	$model = new Rule($item);
    	if($model!=null){
    		if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {

	            return $this->redirect(['index']);
	        }
	        return $this->render('update', ['model' => $model,]);
    	}else{
    		throw new \yii\web\NotFoundHttpException(Yii::t('yii', 'The requested page does not exist.'));
    	}

    }

}

?>