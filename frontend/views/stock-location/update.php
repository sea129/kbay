<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\stocklocation\StockLocation */

$this->title = Yii::t('app/stocklocation', 'Update {modelClass}: ', [
    'modelClass' => 'Stock Location',
]) . ' ' . $model->code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/stocklocation', 'Stock Locations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->code, 'url' => ['view', 'code' => $model->code, 'user_id' => $model->user_id]];
$this->params['breadcrumbs'][] = Yii::t('app/stocklocation', 'Update');
?>
<div class="stock-location-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
