<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use yii\helpers\Url;
use common\widgets\summernote\SummernoteWidget as Summernote;
use frontend\assets\BatchProductAsset;
 ?>
<?php
	BatchProductAsset::register($this);
	$this->registerJsFile("//www.fuelcdn.com/fuelux/3.12.0/js/fuelux.min.js",['depends' => [\yii\web\JqueryAsset::className()]]);

?>
<div class="batch-product-form col-xs-12">
	<?php $form = ActiveForm::begin([
                'options'=>['class'=>'form-horizontal'],
                'enableAjaxValidation' => true,
                'validationUrl' => Url::to(['product/validate-form','id'=>$model->id,'scenario'=>$model::SCENARIO_BATCH]),
                ]); ?>
    <!-- 每单数量 -->
    <?= $form->field($model, 'qty_per_order',[
                    'labelOptions'=>['class'=>'col-sm-2 control-label no-padding-right'],
                    'inputTemplate' => "<div class='col-sm-4' id='qty-per-order'>{input}</div>",
                    'errorOptions' => ['class'=>'help-inline col-sm-6'],
                    ])->textInput(['readonly' =>false,'style'=>'text-align:center;']) ?>
	<?php
          echo $form->field($model, 'sku',[
              'labelOptions'=>['class'=>'col-sm-2 control-label no-padding-right'],
              'inputTemplate' => "<div class='col-sm-4'>{input}</div><div class='col-sm-2'><button type='button' class='btn btn-purple btn-sm' id='auto-sku'>".Yii::t('app/product', 'Generate SKU', [])."</button></div>",
              'errorOptions' => ['class'=>'help-inline col-xs-12 col-sm-4'],
              'inputOptions'=>['class'=>'col-xs-10 col-sm-12'],
          ])->textInput(['maxlength' => true,'readonly' =>false,'disabled'=>false]);


    ?>


	<!-- 名称 -->
    <?= $form->field($model, 'name',[
                    'labelOptions'=>['class'=>'col-sm-2 control-label no-padding-right'],
                    'inputTemplate' => "<div class='col-sm-7'>{input}</div><div class='col-sm-1'><button type='button' class='btn btn-purple btn-sm' id='auto-name'>".Yii::t('app/product', 'Auto Name', [])."</button></div>",
                    'errorOptions' => ['class'=>'help-inline col-xs-12 col-sm-2'],
                    'inputOptions'=>['class'=>'col-xs-10 col-sm-12'],
                ])->textInput(['maxlength' => true]) ?>

	<!-- 进价 -->
	<?= $form->field($model, 'cost',[
                    'labelOptions'=>['class'=>'col-sm-2 control-label no-padding-right'],
                    'inputTemplate' => "<div class='col-sm-3'><span class='input-icon'>{input}<i class='ace-icon bigger-110 fa fa-usd blue'></i></span></div><div class='col-sm-3'><span class='label label-xlg label-purple arrowed'>Single: $".$model->cost."</span></div>",
                    'errorOptions' => ['class'=>'help-inline col-sm-4'],
                    'inputOptions'=>['class'=>'col-sm-12'],
                ])->textInput(['maxlength' => true, 'readonly'=>false]) ?>
	<!-- 重量 -->
    <?= $form->field($model, 'weight',[
                    'labelOptions'=>['class'=>'col-sm-2 control-label no-padding-right'],
                    'inputTemplate' => "<div class='col-sm-3'><span class='input-icon input-icon-right'>{input}<i class='ace-icon blue'>g</i></span></div><div class='col-sm-3'><span class='label label-xlg label-purple arrowed'>Single: ".$model->weight."g</span></div>",
                    'errorOptions' => ['class'=>'help-inline col-sm-4'],
                    'inputOptions'=>['class'=>'col-sm-12','style'=>'text-align:right;'],
                ])->textInput(['readonly'=>false]) ?>
	<!-- 供货商 -->

    <div class="form-group">
    	<label class="col-sm-2 control-label no-padding-right" ><?= Html::encode(Yii::t('app/product', 'Supplier ID')); ?></label>
    	<div class="col-sm-6">
    		<h4>
    			<?php echo $model->supplier_id==null?'Not set':$model->getSuppliers()[$model->supplier_id]; ?>
			</h4>
    	</div>
    </div>


    <!-- 分类 -->

    <div class="form-group">
    	<label class="col-sm-2 control-label no-padding-right" ><?= Html::encode(Yii::t('app/product', 'Category ID')); ?></label>
    	<div class="col-sm-6">
    		<h4>
    			<?php echo $model->category_id==null?'Not set':$model->getCategories()['dropdown'][$model->category_id]; ?>
			</h4>
    	</div>
    </div>


    <!-- 仓库位置 -->
	<div class="form-group">
		<label class="col-sm-2 control-label no-padding-right" ><?= Html::encode(Yii::t('app/product', 'Stock Location')); ?></label>
		<div class="col-sm-6">
			<h4>
				<?php echo $model->stock_location==null?'Not set':$model->getStockLocations()[$model->stock_location]; ?>
			</h4>
		</div>
	</div>
    <!-- 是否加追踪 -->
    <?= $form->field($model, 'is_trackable',[
                    'checkboxTemplate'=> "<div class=\"checkbox col-sm-12\">\n<label class='col-sm-2 control-label no-padding-right' for='product-is_trackable'>{labelTitle}</label><div class='col-sm-2'>{beginLabel}\n{input}\n<span class='lbl' style='margin-left:0 !important;margin-top:5px;'></span>\n{endLabel}</div>\n{error}\n{hint}\n</div>",
                    'errorOptions' => ['class'=>'help-inline col-sm-7'],
                ])->checkbox([
                    'label' => Yii::t('app/product', 'Is Trackable?'),
                    'class' => 'ace ace-switch ace-switch-6',
                ]) ?>

    <!-- 包装邮递 -->
    <?php $this->registerCss(".radio-inline{margin-left:10px;min-width:170px;}"); ?>
    <?= $form->field($model, 'packaging_id',[
                    'labelOptions'=>['class'=>'col-sm-2 control-label no-padding-right'],
                    'inputTemplate' => "<div class='col-sm-8'>{input}</div>",
                    'errorOptions' => ['class'=>'help-inline col-sm-2'],
                    ])->inline()->radioList($model->getAllPackagings()) ?>

    <?php echo $form->field($model, 'mini_desc')->widget(Summernote::className()); ?>

    <!-- 描述 -->
    <?php echo $form->field($model, 'description')->widget(Summernote::className()); ?>

    <!-- 详细参数 -->
    <?= $form->field($model, 'specs')->widget(Summernote::className()); ?>

    <!-- 备注 -->
    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app/app', 'Create') : Yii::t('app/product', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
