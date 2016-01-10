<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\listings\ListingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="listing-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'item_id') ?>

    <?= $form->field($model, 'sku') ?>

    <?= $form->field($model, 'ebay_id') ?>

    <?= $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'qty') ?>

    <?php // echo $form->field($model, 'sold_qty') ?>

    <?php // echo $form->field($model, 'sync_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app/listing', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app/listing', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
