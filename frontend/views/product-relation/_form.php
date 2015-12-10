<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\products\ProductRelation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-relation-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'main')->textInput() ?>

    <?= $form->field($model, 'sub')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app/product', 'Create') : Yii::t('app/product', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
