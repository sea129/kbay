<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\products\ProductRelation */

$this->title = Yii::t('app/product', 'Update {modelClass}: ', [
    'modelClass' => 'Product Relation',
]) . ' ' . $model->main;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/product', 'Product Relations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->main, 'url' => ['view', 'main' => $model->main, 'sub' => $model->sub]];
$this->params['breadcrumbs'][] = Yii::t('app/product', 'Update');
?>
<div class="product-relation-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
