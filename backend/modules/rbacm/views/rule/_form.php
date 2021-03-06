<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model johnitvn\rbacplus\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */
use app\modules\rbacm\Module;

?>

<div class="auth-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'className')->textInput(['maxlength' => true]) ?>

<div class="form-group">
    <?= Html::submitButton(Module::t('module','Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>


    <?php ActiveForm::end(); ?>
    
</div>
