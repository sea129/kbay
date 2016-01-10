<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\listings\Listing */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/listing', 'Listings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="listing-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app/listing', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app/listing', 'Delete'), ['delete', 'id' => $model->id], [
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
            'id',
            'item_id',
            'sku',
            'ebay_id',
            'price',
            'title',
            'qty',
            'sold_qty',
            'sync_at',
        ],
    ]) ?>

</div>
