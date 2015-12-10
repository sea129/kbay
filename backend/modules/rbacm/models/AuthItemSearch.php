<?php

namespace app\modules\rbacm\models;

use yii\data\ArrayDataProvider;
use yii\db\Query;

class AuthItemSearch extends AuthItem
{

    public function rules()
    {
        return [
            [['name', 'description', 'rule_name'], 'safe'],
            
        ];
    }


	public function search($params = [])
	{
		
		$dataProvider = new ArrayDataProvider();

		$query = (new Query)->select(['name', 'description', 'rule_name'])
                ->andWhere(['type' => $this->type])
                ->from(\Yii::$app->authManager->itemTable);

        if ($this->load($params) && $this->validate()) {
            $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'rule_name', $this->rule_name]);
        }
        
        $dataProvider->allModels = $query->all();
        $dataProvider->sort = [
	        'attributes' => ['name', 'description','rule_name'],
	    ];
        
        return $dataProvider;
	}
    
}





?>