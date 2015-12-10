<?php

namespace frontend\models\stocklocation;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\stocklocation\StockLocation;

/**
 * StockLocationSearch represents the model behind the search form about `frontend\models\stocklocation\StockLocation`.
 */
class StockLocationSearch extends StockLocation
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code'], 'safe'],
            [['user_id'], 'integer'],
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
        $query = StockLocation::find();

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
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }
}
