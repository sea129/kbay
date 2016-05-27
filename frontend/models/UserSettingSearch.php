<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\UserSetting;

/**
 * UserSettingSearch represents the model behind the search form about `frontend\models\UserSetting`.
 */
class UserSettingSearch extends UserSetting
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'min_cost_tracking', 'fastway_indicator'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = UserSetting::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'min_cost_tracking' => $this->min_cost_tracking,
            'fastway_indicator' => $this->fastway_indicator,
        ]);

        return $dataProvider;
    }
}
