<?php

namespace frontend\models\listings;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\listings\Listing;

/**
 * ListingSearch represents the model behind the search form about `frontend\models\listings\Listing`.
 */
class ListingSearch extends Listing
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ebay_id', 'qty', 'sold_qty'], 'integer'],
            [['item_id', 'sku', 'title', 'sync_at'], 'safe'],
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
        $query = Listing::find()
              ->innerJoin('ebay_account','listing.ebay_id = ebay_account.id')
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
            'id' => $this->id,
            'ebay_id' => $this->ebay_id,
            'price' => $this->price,
            'qty' => $this->qty,
            'sold_qty' => $this->sold_qty,
            'sync_at' => $this->sync_at,
        ]);

        $query->andFilterWhere(['like', 'item_id', $this->item_id])
            ->andFilterWhere(['like', 'sku', $this->sku])
            ->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
