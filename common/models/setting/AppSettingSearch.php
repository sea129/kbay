<?php

namespace common\models\setting;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\setting\AppSetting;

/**
 * AppSettingSearch represents the model behind the search form about `common\models\setting\AppSetting`.
 */
class AppSettingSearch extends AppSetting
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'string_value', 'updated_at'], 'safe'],
            [['number_value'], 'integer'],
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
        $query = AppSetting::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'number_value' => $this->number_value,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'string_value', $this->string_value]);

        return $dataProvider;
    }
}
