<?php

namespace frontend\models\productebaylisting;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\productebaylisting\ProductEbayListing;

/**
 * SearchListing represents the model behind the search form about `frontend\models\productebaylisting\ProductEbayListing`.
 */
class SearchListing extends ProductEbayListing
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sku', 'item_id', 'title', 'updated_at'], 'safe'],
            [['ebay_account_id', 'qty', 'qty_sold'], 'integer'],
            [['price'], 'number'],
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
        $query = ProductEbayListing::find()
              ->innerJoin('ebay_account','product_ebay_listing.ebay_account_id = ebay_account.id')
              ->where(['ebay_account.user_id'=>Yii::$app->user->id]);

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
            'ebay_account_id' => $this->ebay_account_id,
            'price' => $this->price,
            'updated_at' => $this->updated_at,
            'qty' => $this->qty,
            'qty_sold' => $this->qty_sold,
        ]);

        $query->andFilterWhere(['like', 'sku', $this->sku])
            ->andFilterWhere(['like', 'item_id', $this->item_id])
            ->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
