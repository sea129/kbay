<?php


use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<?php $this->registerCss(".ui-menu .ui-menu-item:hover,.ui-menu .ui-state-active {background:#4f99c6}"); ?>
<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'name') ?>

<?= $form->field($model, 'description') ?>

<?php //echo  $form->field($model, 'rule_name') ?>
<?php echo $form->field($model, 'rule_name')->widget(\yii\jui\AutoComplete::className(),[

	'clientOptions'=>[
		'source' => array_keys(\Yii::$app->authManager->getRules()),
	],
	'options'=>['class'=>'form-control',],
]) ?>
<?= $form->field($model, 'data')->textarea(['rows' => 6]) ?>

<?= Html::submitButton('Save', ['class' => 'btn btn-success btn-block']) ?>

<?php ActiveForm::end() ?>