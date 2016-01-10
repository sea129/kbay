<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\productebaylisting\ProductEbayListing */

$this->title = Yii::t('app/listing', 'Create Product Ebay Listing');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/listing', 'Product Ebay Listings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-ebay-listing-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
