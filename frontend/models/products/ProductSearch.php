<?php

namespace frontend\models\products;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\products\Product;

/**
 * ProductSearch represents the model behind the search form about `frontend\models\products\Product`.
 */
class ProductSearch extends Product
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'stock_qty', 'category_id', 'user_id', 'supplier_id', 'weight', 'is_trackable', 'qty_per_order'], 'integer'],
            [['sku', 'name', 'mini_desc', 'description', 'specs', 'stock_location', 'comment'], 'safe'],
            [['cost'], 'number'],
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
        $query = Product::find();

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
            'id' => $this->id,
            'stock_qty' => $this->stock_qty,
            'cost' => $this->cost,
            'category_id' => $this->category_id,
            'user_id' => $this->user_id,
            'supplier_id' => $this->supplier_id,
            'weight' => $this->weight,
            'is_trackable' => $this->is_trackable,
            'qty_per_order' => $this->qty_per_order,
        ]);

        $query->andFilterWhere(['like', 'sku', $this->sku])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'mini_desc', $this->mini_desc])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'specs', $this->specs])
            ->andFilterWhere(['like', 'stock_location', $this->stock_location])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }

    public function shortSearch($params)
    {
        $query = Product::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'sku', $this->sku])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
