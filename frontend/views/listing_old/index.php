<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\productebaylisting\SearchListing */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app/listing', 'Product Ebay Listings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-ebay-listing-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app/listing', 'Create Product Ebay Listing'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app/listing', 'Sync Listings'), ['sync'], ['class' => 'btn btn-danger']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'sku',
            'ebay_account_id',
            'item_id',
            'price',
            'title',
            // 'updated_at',
            // 'qty',
            // 'qty_sold',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
