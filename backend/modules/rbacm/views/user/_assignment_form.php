<?php


use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<?= Html::beginForm(['user/update-assignment'], 'post',[]) ?>
<div class="row">
  <div class="col-xs-12 col-sm-6">
    <div class="control-group">
      <label class="control-label bolder blue">Roles:</label>
      <?= Html::checkboxList('role_assignment',array_combine($assignedRoles,$assignedRoles),array_combine($availableRoles,$availableRoles),['unselect'=>'no role assignment',
        'item'=>function($index, $label, $name, $checked, $value){
          
          return Html::beginTag('div',['class'=>'checkbox']).Html::checkbox($name,$checked,['label'=>"<span class='lbl'>&nbsp;".$label.'</span>','class'=>'ace','value'=>$value]).Html::endTag('div');
        }]) ?>
    </div>
  </div>
  <div class="col-xs-12 col-sm-6">
    <label class="control-label bolder blue">Permissions:</label>
    <?php echo Html::ul(array_keys($assignedPermissions),['class'=>'list-unstyled spaced','item'=>function($item,$index){return "<li><i class='ace-icon fa fa-check bigger-110 green'></i>".$item."</li>";}]); ?>
  </div>
</div>
<?= Html::hiddenInput('userID',$model->id) ?>

<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>

<?= Html::endForm() ?>

<?php 


/*  echo '<pre>';
  print_r($assignedPermissions);
  echo '</pre>';
 


    echo '<pre>';
    print_r($availableRoles);
    echo '</pre>';
    


      echo '<pre>';
      print_r($assignedRoles);
      echo '</pre>';
      exit();*/
 ?>