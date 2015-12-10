<?php

namespace frontend\models\ebayaccounts;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\ebayaccounts\EbayAccount;

/**
 * EbayAccountSearch represents the model behind the search form about `frontend\models\ebayaccounts\EbayAccount`.
 */
class EbayAccountSearch extends EbayAccount
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'listing_template_id'], 'integer'],
            [['seller_id', 'store_id', 'shipping_info', 'warranty_info', 'payment_info', 'contact_info', 'token', 'token_expiration', 'email', 'listing_assets_url'], 'safe'],
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
        $query = EbayAccount::find();

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
            'user_id' => $this->user_id,
            'listing_template_id' => $this->listing_template_id,
            'token_expiration' => $this->token_expiration,
        ]);

        $query->andFilterWhere(['like', 'seller_id', $this->seller_id])
            ->andFilterWhere(['like', 'store_id', $this->store_id])
            ->andFilterWhere(['like', 'shipping_info', $this->shipping_info])
            ->andFilterWhere(['like', 'warranty_info', $this->warranty_info])
            ->andFilterWhere(['like', 'payment_info', $this->payment_info])
            ->andFilterWhere(['like', 'contact_info', $this->contact_info])
            ->andFilterWhere(['like', 'token', $this->token])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'listing_assets_url', $this->listing_assets_url]);

        return $dataProvider;
    }
}
