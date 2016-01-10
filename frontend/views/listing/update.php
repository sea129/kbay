<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\listings\Listing */

$this->title = Yii::t('app/listing', 'Update {modelClass}: ', [
    'modelClass' => 'Listing',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/listing', 'Listings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app/listing', 'Update');
?>
<div class="listing-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
