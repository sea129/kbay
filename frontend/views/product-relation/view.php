<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\products\ProductRelation */

$this->title = $model->main;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/product', 'Product Relations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-relation-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app/product', 'Update'), ['update', 'main' => $model->main, 'sub' => $model->sub], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app/product', 'Delete'), ['delete', 'main' => $model->main, 'sub' => $model->sub], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app/product', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'main',
            'sub',
        ],
    ]) ?>

</div>
