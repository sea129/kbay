<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
//use yii\grid\GridView;
use yii\helpers\Url;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\listings\ListingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
use frontend\models\ebayaccounts\EbayAccount;
$this->title = Yii::t('app/listing', 'Listings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="listing-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //= Html::a(Yii::t('app/listing', 'Create Listing'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            'item_id',
            //'sku',
            [
              'attribute'=>'sku',
              'value'=>function($model, $key, $index, $widget){
                return Html::a($model->sku,Url::to(['product/view','id'=>$model->product->id]),['target'=>'_blank']);
              },
              'format'=>'raw',
            ],
            //'ebay_id',
            [
              'attribute'=>'ebay_id',
              'value'=>function($model, $key, $index, $widget){
                return $model->ebay->seller_id;
              },
              'vAlign'=>'middle',
              'width'=>'180px',
              'label'=>Yii::t('app/order', 'eBay Account', []),
              'filterType'=>GridView::FILTER_SELECT2,
              'filter'=>ArrayHelper::map(EbayAccount::find()->where(['user_id'=>Yii::$app->user->id])->orderBy('seller_id')->asArray()->all(), 'id', 'seller_id'),
              'filterWidgetOptions'=>[
                  'pluginOptions'=>['allowClear'=>true],
              ],
              'filterInputOptions'=>['placeholder'=>'eBay Account'],
              'format'=>'raw',
            ],
            'price',
            'title',
            'qty',
            'sold_qty',
            'sync_at',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
