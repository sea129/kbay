<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\UserSetting */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-setting-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'min_cost_tracking')->textInput()->label('Minimum Product Cost For Tracking Shipping Label ($)') ?>

    <?php //= $form->field($model, 'fastway_indicator')->textInput() ?>
    <?php echo $form->field($model, 'fastway_indicator')->checkbox() ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
