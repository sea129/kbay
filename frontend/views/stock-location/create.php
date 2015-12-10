<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\stocklocation\StockLocation */

$this->title = Yii::t('app/stocklocation', 'Create Stock Location');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/stocklocation', 'Stock Locations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-location-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
