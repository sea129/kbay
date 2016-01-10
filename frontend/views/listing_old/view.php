<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\productebaylisting\ProductEbayListing */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/listing', 'Product Ebay Listings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-ebay-listing-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app/listing', 'Update'), ['update', 'id' => $model->item_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app/listing', 'Delete'), ['delete', 'id' => $model->item_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app/listing', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'sku',
            'ebay_account_id',
            'item_id',
            'price',
            'title',
            'updated_at',
            'qty',
            'qty_sold',
        ],
    ]) ?>

</div>
