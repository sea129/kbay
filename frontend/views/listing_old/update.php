<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\productebaylisting\ProductEbayListing */

$this->title = Yii::t('app/listing', 'Update {modelClass}: ', [
    'modelClass' => 'Product Ebay Listing',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/listing', 'Product Ebay Listings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->item_id]];
$this->params['breadcrumbs'][] = Yii::t('app/listing', 'Update');
?>
<div class="product-ebay-listing-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
