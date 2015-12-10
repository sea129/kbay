<?php


use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<?php 
	



 ?>

<?= Html::beginForm(['role/update-children'], 'post',[]) ?>
<div class="row">
	<div class="col-xs-12 col-sm-6">
		<div class="control-group">
			<label class="control-label bolder blue">Roles:</label>
			<?= Html::checkboxList('children',$assigned['Roles'],$avaliable['Roles'],['unselect'=>'no children',
				'item'=>function($index, $label, $name, $checked, $value){
					
					return Html::beginTag('div',['class'=>'checkbox']).Html::checkbox($name,$checked,['label'=>"<span class='lbl'>&nbsp;".$label.'</span>','class'=>'ace','value'=>$value]).Html::endTag('div');
				}]) ?>
		</div>
	</div>

	<div class="col-xs-12 col-sm-6">
		<div class="control-group">
			<label class="control-label bolder blue">Permissions:</label>
			<?= Html::checkboxList('children',$assigned['Permissions'],$avaliable['Permissions'],[
				'item'=>function($index, $label, $name, $checked, $value){
					
					return Html::beginTag('div',['class'=>'checkbox']).Html::checkbox($name,$checked,['label'=>"<span class='lbl'>&nbsp;".$label.'</span>','class'=>'ace','value'=>$value]).Html::endTag('div');
				}]) ?>
		</div>
	</div>
	<div class="col-xs-12 col-sm-6"></div>
</div>
<? //echo Html::checkboxList('children',$assigned['Permissions'],$avaliable['Permissions']); ?>
<?= Html::hiddenInput('name',$model->name) ?>

<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>

<?= Html::endForm() ?>