<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\listings\ListingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app/listing', 'Listings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="listing-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app/listing', 'Create Listing'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'item_id',
            'sku',
            'ebay_id',
            'price',
            // 'title',
            // 'qty',
            // 'sold_qty',
            // 'sync_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
