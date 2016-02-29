<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use frontend\assets\ProductAsset;
use common\widgets\summernote\SummernoteWidget as Summernote;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use frontend\models\category\Category;
use frontend\models\supplier\Supplier;
use frontend\models\stocklocation\StockLocation;
use yii\widgets\Pjax;

use kartik\file\FileInput;
//use common\widgets\fileinput\FileInputWidget;

/* @var $this yii\web\View */
/* @var $model frontend\models\products\Product */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$this->registerJsFile("//www.fuelcdn.com/fuelux/3.12.0/js/fuelux.min.js",['depends' => [\yii\web\JqueryAsset::className()]]);
ProductAsset::register($this);
 ?>
<div class="product-form col-xs-12">

    <?php $form = ActiveForm::begin([
                'options'=>['class'=>'form-horizontal','enctype'=>'multipart/form-data'],
                'enableAjaxValidation' => true,
                'validationUrl' => Url::to(['product/validate-form','id'=>$model->id]),
                ]); ?>
    <!-- 图片 -->

    <div class="form-group">

        <label class="col-sm-2 control-label no-padding-right" for="product-sku"><?= Html::encode(Yii::t('app/product', 'Uploaded Product Image')); ?></label>
        <div class="col-sm-2">
          <?php if(isset($model->main_image)){//update product ?>
              <img src='<?php echo $model->main_image; ?>' width='200' id='main_image_ph'>
            <?php }else{//create product ?>
              <img src='<?php echo Url::to('/images/no-product-image.png') ?>' width='200' id='main_image_ph'>
            <?php } ?>
        </div>

        <div class="col-sm-6">
        <?php Pjax::begin(['id' => 'product-image']); ?>
        <?php
              echo FileInput::widget([
                'name'=>'main-image-upload',
                'pluginOptions' => [
                    'maxFileSize'=>'200',//kb
                    'allowedFileTypes' => ['image'],
                    'allowedFileExtensions'=> ["jpg", "png", "gif"],
                    'previewFileType'=>'image',
                    'browseClass' => 'btn btn-success btn-sm',
                    'uploadClass' => 'btn btn-danger btn-sm',
                    'removeClass' => 'btn btn-info btn-sm',
                    'maxFileCount' => 1,
                    // 'maxImageWidth' => 250,
                    // 'maxImageHeight' => 250,
                    // 'resizeImage' => true,
                    'uploadUrl' => Url::to('http://uploads.im/api'),
                    'layoutTemplates'=>['footer'=>''],

                ],
                'pluginEvents'=>[
                  'filepreupload'=>'function(event, data, previewId, index){
                    data.jqXHR.abort();
                    uploadMainImage(data.files);
                  }',

                ],
              ]);
        ?>
        <?php Pjax::end(); ?>
        </div>
        <div class="col-sm-2">
          <?php echo $form->field($model,'main_image',['labelOptions'=>['style'=>'display:none;'],'options'=>['style'=>'']])->hiddenInput(); ?>
        </div>
    </div>


    <!-- 分类 -->
    <?php Pjax::begin(['id' => 'category-selection']); ?>
    <?php $categoryArray = $model->getCategories(); ?>
    <?= $form->field($model, 'category_id',[
                    'labelOptions'=>['class'=>'col-sm-2 control-label no-padding-right'],
                    'inputTemplate' => "<div class='col-sm-4'>{input}</div><div class='col-sm-2'><button type='button' class='btn btn-sm btn-inverse' data-toggle='modal' data-target='#create-category'>".Yii::t('app/category', 'Create Category')."</button></div>",
                    'errorOptions' => ['class'=>'help-inline col-sm-4'],
                    'inputOptions'=>['class'=>'col-xs-10 col-sm-7'],
                ])->dropDownList($categoryArray['dropdown'],['class'=>'form-control','prompt'=>Yii::t('app/product', 'Choose a category...'),'options'=>$categoryArray['option']]); ?>
    <?php Pjax::end(); ?>

    <!-- SKU -->
    <?php

         if($this->context->action->id==='create'){

            echo $form->field($model, 'sku',[
                    'labelOptions'=>['class'=>'col-sm-2 control-label no-padding-right'],
                    'inputTemplate' => "<div class='col-sm-4'>{input}</div><div class='col-sm-1'><button type='button' class='btn btn-purple btn-sm' id='auto-sku'>".Yii::t('app/product', 'Generate SKU', [])."</button></div>",
                    'errorOptions' => ['class'=>'help-inline col-xs-12 col-sm-3'],
                    'inputOptions'=>['class'=>'col-xs-10 col-sm-12'],
                ])->textInput(['maxlength' => true,'readonly' =>false,'disabled'=>false]);

         }
     ?>

    <!-- 名称 -->

    <?= $form->field($model, 'name',[
                    'labelOptions'=>['class'=>'col-sm-2 control-label no-padding-right'],
                    'inputTemplate' => "<div class='col-sm-9'>{input}</div>",
                    'errorOptions' => ['class'=>'help-inline col-xs-12 col-sm-1'],
                    'inputOptions'=>['class'=>'col-xs-10 col-sm-12'],
                ])->textInput(['maxlength' => true]) ?>

    <!-- 库存 -->

    <?php echo $form->field($model, 'stock_qty',[
                    'labelOptions'=>['class'=>'col-sm-2 control-label no-padding-right'],
                    'inputTemplate' => "<div class='col-sm-1 ace-spinner middle spinbox' data-initialize='spinbox'>
                                            <div class='input-group'>
                                                {input}
                                                <div class='spinbox-buttons input-group-btn btn-group-vertical' id='spinner-hack'>
                                                    <button type='button' class='btn spinbox-up btn-sm btn-info'>
                                                    <i class=' ace-icon fa fa-chevron-up'></i>
                                                     </button>
                                                     <button type='button' class='btn spinbox-down btn-sm btn-info'>
                                                     <i class=' ace-icon fa fa-chevron-down'></i>
                                                     </button>
                                                </div>
                                            </div>
                                        </div>",
                    'errorOptions' => ['class'=>'help-inline col-sm-4'],
                    'inputOptions'=>['class'=>'col-xs-10 col-sm-12 spinbox-input form-control text-center'],
                ])->textInput(); ?>


    <!-- <div class='form-group'>
        <label class="col-sm-2 control-label no-padding-right" for=""></label>
        <div class='col-sm-10'>
            <input type="hidden" name='main-product-id' id='main-product-id'>
            <button type='button' class='btn btn-sm btn-inverse col-sm-2' id='btn-product-relation' data-toggle='modal' data-target='#product-relation'><?php //echo Yii::t('app/product', 'Link to a main product') ?></button>
        </div>
    </div> -->
    <!-- 进价 -->
    <?= $form->field($model, 'cost',[
                    'labelOptions'=>['class'=>'col-sm-2 control-label no-padding-right'],
                    'inputTemplate' => "<div class='col-sm-3'><span class='input-icon'>{input}<i class='ace-icon bigger-110 fa fa-usd blue'></i></span></div>",
                    'errorOptions' => ['class'=>'help-inline col-sm-6'],
                    'inputOptions'=>['class'=>'col-sm-12'],
                ])->textInput(['maxlength' => true]) ?>


    <!-- 供货商 -->
    <?php Pjax::begin(['id' => 'supplier-selection']); ?>
    <?= $form->field($model, 'supplier_id',[
                    'labelOptions'=>['class'=>'col-sm-2 control-label no-padding-right'],
                    'inputTemplate' => "<div class='col-sm-4'>{input}</div><div class='col-sm-2'><button type='button' class='btn btn-sm btn-inverse' data-toggle='modal' data-target='#create-supplier'>".Yii::t('app/supplier', 'Create Supplier')."</button></div>",
                    'errorOptions' => ['class'=>'help-inline col-sm-4'],
                    'inputOptions'=>['class'=>'col-xs-10 col-sm-7'],
                ])->dropDownList($model->getSuppliers(),['class'=>'form-control','prompt'=>Yii::t('app/supplier', 'Choose a supplier...')]); ?>
    <?php Pjax::end(); ?>



    <!-- 仓库位置 -->

    <?php Pjax::begin(['id' => 'stocklocation-selection']); ?>
    <?= $form->field($model, 'stock_location',[
                    'labelOptions'=>['class'=>'col-sm-2 control-label no-padding-right'],
                    'inputTemplate' => "<div class='col-sm-4'>{input}</div><div class='col-sm-2'><button type='button' class='btn btn-sm btn-inverse' data-toggle='modal' data-target='#create-stocklocation'>".Yii::t('app/stocklocation', 'Create Location')."</button></div>",
                    'errorOptions' => ['class'=>'help-inline col-sm-4'],
                    'inputOptions'=>['class'=>'col-xs-10 col-sm-7'],
                ])->dropDownList($model->getStockLocations(),['class'=>'form-control','prompt'=>Yii::t('app/stocklocation', 'Choose a location...')]); ?>
    <?php Pjax::end(); ?>


    <!-- 重量 -->
    <?= $form->field($model, 'weight',[
                    'labelOptions'=>['class'=>'col-sm-2 control-label no-padding-right'],
                    'inputTemplate' => "<div class='col-sm-3'><span class='input-icon input-icon-right'>{input}<i class='ace-icon blue'>g</i></span></div>",
                    'errorOptions' => ['class'=>'help-inline col-sm-7'],
                    'inputOptions'=>['class'=>'col-sm-12','style'=>'text-align:right;'],
                ])->textInput() ?>

    <!-- 是否加追踪 -->
    <?= $form->field($model, 'is_trackable',[
                    'checkboxTemplate'=> "<div class=\"checkbox col-sm-12\">\n<label class='col-sm-2 control-label no-padding-right' for='product-is_trackable'>{labelTitle}</label><div class='col-sm-2'>{beginLabel}\n{input}\n<span class='lbl' style='margin-left:0 !important;margin-top:5px;'></span>\n{endLabel}</div>\n{error}\n{hint}\n</div>",
                    'errorOptions' => ['class'=>'help-inline col-sm-7'],
                ])->checkbox([
                    'label' => Yii::t('app/product', 'Is Trackable?'),
                    'class' => 'ace ace-switch ace-switch-6',
                ]) ?>

    
    <!-- 每单数量 -->
    <?= $form->field($model, 'qty_per_order',[
                    'labelOptions'=>['class'=>'col-sm-2 control-label no-padding-right'],
                    'inputTemplate' => "<div class='col-sm-2' id='qty-per-order'>{input}</div>",
                    'errorOptions' => ['class'=>'help-inline col-sm-8'],
                    ])->textInput(['readonly' =>true,'style'=>'text-align:center;']) ?>

    <!-- 简练描述 -->
    <?= $form->field($model, 'mini_desc')->widget(Summernote::className()); ?>

    <!-- 描述 -->
    <?= $form->field($model, 'description')->widget(Summernote::className()); ?>

    <!-- 详细参数 -->
    <?= $form->field($model, 'specs')->widget(Summernote::className()); ?>

    <!-- 备注 -->
    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app/app', 'Create') : Yii::t('app/product', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<!-- Category create form -->
<?php
    Modal::begin([
        'header' => Yii::t('app/category', 'Create Category'),
        'options' =>['id'=>'create-category'],
    ]);

    $categoryModel = new Category();
?>
<?php echo $this->render('_loading_div', [
        'container' => 'loading-category',
        'button' => 'close-modal-category',
    ]); ?>
<div class="category-form" style=''>

    <?php $caretegoryForm = ActiveForm::begin([
                'action'=>Url::to(['category/create']),
                'id'=>'category-form',
                'enableAjaxValidation' => true,
                'validationUrl' => Url::to(['category/validate-create']),

            ]); ?>

    <?= $caretegoryForm->field($categoryModel, 'name')->textInput(['maxlength' => true]) ?>

    <?= $caretegoryForm->field($categoryModel, 'code')->textInput(['maxlength' => true]) ?>

    <?= $caretegoryForm->field($categoryModel, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($categoryModel->isNewRecord ? Yii::t('app/category', 'Create') : Yii::t('app/category', 'Update'), ['class' => $categoryModel->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php Modal::end(); ?>
<!-- category create form end -->

<!-- supplier create form start -->
<?php
    Modal::begin([
        'header' => Yii::t('app/supplier', 'Create Supplier'),
        'options' =>['id'=>'create-supplier'],
    ]);

    $supplierModel = new Supplier();
?>
<?php echo $this->render('_loading_div', [
        'container' => 'loading-supplier',
        'button' => 'close-modal-supplier',
    ]); ?>
<div class="supplier-form" style=''>

    <?php $supplierForm = ActiveForm::begin([
                'action'=>Url::to(['supplier/create']),
                'id'=>'supplier-form',
                'enableAjaxValidation' => true,
                'validationUrl' => Url::to(['supplier/validate-create']),

            ]); ?>

    <?= $supplierForm->field($supplierModel, 'name')->textInput(['maxlength' => true]) ?>

    <?= $supplierForm->field($supplierModel, 'address')->textInput(['maxlength' => true]) ?>

    <?= $supplierForm->field($supplierModel, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $supplierForm->field($supplierModel, 'email')->textInput(['maxlength' => true]) ?>

    <?= $supplierForm->field($supplierModel, 'comment')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($supplierModel->isNewRecord ? Yii::t('app/supplier', 'Create') : Yii::t('app/supplier', 'Update'), ['class' => $supplierModel->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php Modal::end(); ?>
<!-- supplier create form end -->

<!-- Stock location create form start -->
<?php
    Modal::begin([
        'header' => Yii::t('app/stocklocation', 'Create Location'),
        'options' =>['id'=>'create-stocklocation'],
    ]);

    $stocklocationModel = new StockLocation();
?>
<?php echo $this->render('_loading_div', [
        'container' => 'loading-stocklocation',
        'button' => 'close-modal-stocklocation',
    ]); ?>
<div class="stocklocation-form" style=''>

    <?php $stocklocationForm = ActiveForm::begin([
                'action'=>Url::to(['stock-location/create']),
                'id'=>'stocklocation-form',
                'enableAjaxValidation' => true,
                'validationUrl' => Url::to(['stock-location/validate-create']),

            ]); ?>

     <?= $stocklocationForm->field($stocklocationModel, 'code')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'user_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($stocklocationModel->isNewRecord ? Yii::t('app/stocklocation', 'Create') : Yii::t('app/stocklocation', 'Update'), ['class' => $stocklocationModel->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php Modal::end(); ?>
<!-- stock location create form end -->
