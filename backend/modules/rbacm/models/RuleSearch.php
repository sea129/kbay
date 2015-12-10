<?php

namespace app\modules\rbacm\models;

use Yii;
use yii\data\ArrayDataProvider;
 
class RuleSearch extends Rule {

    public function rules() {
        return [
            [['name','className'], 'safe']
        ];
    }

    
    public function search($params) {
        $this->load($params);

        $authManager = Yii::$app->authManager;
        
        return new ArrayDataProvider([
            'allModels' => $authManager->getRules(),
        ]);
    }

}
