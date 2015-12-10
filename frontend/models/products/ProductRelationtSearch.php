<?php

namespace frontend\models\products;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\products\ProductRelation;

/**
 * ProductRelationtSearch represents the model behind the search form about `frontend\models\products\ProductRelation`.
 */
class ProductRelationtSearch extends ProductRelation
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['main', 'sub'], 'integer'],
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
        $query = ProductRelation::find();

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
            'main' => $this->main,
            'sub' => $this->sub,
        ]);

        return $dataProvider;
    }
}
