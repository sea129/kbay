<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\setting\AppSetting */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="app-setting-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php if($model->isNewRecord){ ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?php }else{ ?>
    <?php echo $model->name; ?>
    <?php } ?>
    <?= $form->field($model, 'number_value')->textInput() ?>

    <?= $form->field($model, 'string_value')->textInput(['maxlength' => true]) ?>

    <?php //= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app/setting', 'Create') : Yii::t('app/setting', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
