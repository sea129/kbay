<?php

namespace frontend\models\orders;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\orders\EOrder;
use yii\helpers\StringHelper;
/**
 * EOrderSearch represents the model behind the search form about `frontend\models\orders\EOrder`.
 */
class EOrderSearch extends EOrder
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'ebay_id', 'user_id'], 'integer'],
            [['total','fetched_at', 'ebay_order_id', 'ebay_seller_id', 'buyer_id', 'created_time','shipped_time', 'paid_time', 'recipient_name', 'recipient_phone', 'recipient_address1', 'recipient_address2', 'recipient_city', 'recipient_state', 'recipient_postcode', 'checkout_message','sale_record_number'], 'safe'],
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
    public function getNonLabeled($ebayIDArr)
    {
      // $subQuery = (new \yii\db\Query())
      //             ->select(['t.buyer_id','COUNT(t.buyer_id) as buyer_count', 't.id'])
      //             ->from('ebay_order t')
      //             ->where(['t.label'=>0,'t.status'=>0,'t.user_id'=>Yii::$app->user->id, 't.ebay_id'=>$ebayIDArr])
      //             ->andWhere(['is not', 't.paid_time', null])
      //             //->innerJoin(['z'=>$k],'z.ebay_order_id=t.id')
      //             ->groupBy(['t.buyer_id'])
      //             //->all();
      //             ;
      //             //return $subQuery;
      $subQuery = (new \yii\db\Query())
                  ->select(['buyer_id','COUNT(buyer_id) as buyer_count'])
                  ->from('ebay_order')
                  ->where(['label'=>0,'status'=>0,'user_id'=>Yii::$app->user->id, 'ebay_id'=>$ebayIDArr])
                  ->andWhere(['is not', 'paid_time', null])
                  //->innerJoin(['z'=>$k],'z.ebay_order_id=t.id')
                  ->groupBy(['buyer_id'])
                  //->all();
                  ;
                  //return $subQuery;
      $querySku = (new \yii\db\Query())
                ->select(['ebay_order_id','item_sku'])
                ->from('ebay_transaction')
                //->leftJoin(['y'=>$subQuery], 'y.id=ebay_order_id')
                //->all()
                ;
      //return $querySku;
      $orderModal = (new \yii\db\Query())
                    ->select(['x.*','y.item_sku','z.buyer_count'])
                    ->from('ebay_order x')
                    ->where(['label'=>0,'status'=>0,'user_id'=>Yii::$app->user->id, 'ebay_id'=>$ebayIDArr])
                    ->andWhere(['is not', 'paid_time', null])
                    ->leftJoin(['y' => $querySku],'x.id=y.ebay_order_id')
                    ->leftJoin(['z' => $subQuery],'x.buyer_id=z.buyer_id')
                    ->groupBy('x.id')
                    ->orderBy('y.item_sku')
                    ->all();
      // $orderModal = (new \yii\db\Query())
      //               ->select(['x.*','y.buyer_count','y.item_sku'])
      //               ->from('ebay_order x')
      //               ->where(['x.label'=>0,'x.status'=>0,'x.user_id'=>Yii::$app->user->id, 'x.ebay_id'=>$ebayIDArr])
      //               ->andWhere(['is not', 'x.paid_time', null])
      //               ->leftJoin(['y' => $subQuery],'y.buyer_id=x.buyer_id')
      //               ->all();
      return $orderModal;
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
        $query = EOrder::find();

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
            //'id' => $this->id,
            //'type' => $this->type,
            //'fetched_at' => $this->fetched_at,
            'status' => $this->status,
            'ebay_id' => $this->ebay_id,
            'sale_record_number' => $this->sale_record_number,
            //'user_id' => $this->user_id,
            //'created_time' => $this->created_time,
            //'paid_time' => $this->paid_time,
        ]);

        $query->andFilterWhere(['like', 'ebay_order_id', $this->ebay_order_id])
            //->andFilterWhere(['like', 'ebay_seller_id', $this->ebay_seller_id])
            ->andFilterWhere(['like', 'buyer_id', $this->buyer_id])
            ->andFilterWhere(['like', 'total', $this->total])
             ->andFilterWhere(['like', 'recipient_name', $this->recipient_name]);
            // ->andFilterWhere(['like', 'recipient_phone', $this->recipient_phone])
            // ->andFilterWhere(['like', 'recipient_address1', $this->recipient_address1])
            // ->andFilterWhere(['like', 'recipient_address2', $this->recipient_address2])
            // ->andFilterWhere(['like', 'recipient_city', $this->recipient_city])
            // ->andFilterWhere(['like', 'recipient_state', $this->recipient_state])
            // ->andFilterWhere(['like', 'recipient_postcode', $this->recipient_postcode])
            // ->andFilterWhere(['like', 'checkout_message', $this->checkout_message]);

        // if ($this->fetched_at && $fetchedDateRange = explode(" - ",$this->fetched_at)) {
        //     if(isset($fetchedDateRange[0])&&isset($fetchedDateRange[1])){
        //       $query->andFilterWhere(['between','fetched_at',$fetchedDateRange[0],$fetchedDateRange[1]]);
        //     }
        // }
        if ($this->created_time && $dateRange = explode(" - ",$this->created_time)) {
          if(isset($dateRange[0])&&isset($dateRange[1])){
            $startDate = new \DateTime($dateRange[0],new \DateTimeZone('Australia/Sydney'));
            $endDate = new \DateTime($dateRange[1],new \DateTimeZone('Australia/Sydney'));
            $startDate->setTimezone(new \DateTimeZone('GMT'))->format('Y-m-d H:i:s');
            $startDate = $startDate->format('Y-m-d H:i:s');
            $endDate->setTimezone(new \DateTimeZone('GMT'))->format('Y-m-d H:i:s');
            $endDate = $endDate->format('Y-m-d H:i:s');
            $query->andFilterWhere(['between','created_time',$startDate,$endDate]);
          }
        }
        if ($this->paid_time && $dateRange = explode(" - ",$this->paid_time)) {
          if(isset($dateRange[0])&&isset($dateRange[1])){
            $startDate = new \DateTime($dateRange[0],new \DateTimeZone('Australia/Sydney'));
            $endDate = new \DateTime($dateRange[1],new \DateTimeZone('Australia/Sydney'));
            $startDate->setTimezone(new \DateTimeZone('GMT'))->format('Y-m-d H:i:s');
            $startDate = $startDate->format('Y-m-d H:i:s');
            $endDate->setTimezone(new \DateTimeZone('GMT'))->format('Y-m-d H:i:s');
            $endDate = $endDate->format('Y-m-d H:i:s');
            $query->andFilterWhere(['between','paid_time',$startDate,$endDate]);
          }
        }
        if ($this->shipped_time && $dateRange = explode(" - ",$this->shipped_time)) {
          if(isset($dateRange[0])&&isset($dateRange[1])){
            $startDate = new \DateTime($dateRange[0],new \DateTimeZone('Australia/Sydney'));
            $endDate = new \DateTime($dateRange[1],new \DateTimeZone('Australia/Sydney'));
            $startDate->setTimezone(new \DateTimeZone('GMT'))->format('Y-m-d H:i:s');
            $startDate = $startDate->format('Y-m-d H:i:s');
            $endDate->setTimezone(new \DateTimeZone('GMT'))->format('Y-m-d H:i:s');
            $endDate = $endDate->format('Y-m-d H:i:s');
            $query->andFilterWhere(['between','shipped_time',$startDate,$endDate]);
          }
        }
        return $dataProvider;
    }
}
