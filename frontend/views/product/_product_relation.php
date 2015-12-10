<?php 
use \yii\jui\AutoComplete;
use frontend\models\products\Product;
 ?>

 <div class="row">
	<div class="col-xs-12 col-sm-8">
		<div class="input-group">
			<span class="input-group-addon">
				<i class="ace-icon fa fa-cube"></i>
			</span>
 <?php 
 $this->registerCss('.ui-autocomplete{z-index:1060}');
 $mainSKUs = Product::find()->where(['is not', 'stock_qty', null])->andWhere(['qty_per_order'=>1])->asArray()->indexBy('sku')->all();

 echo AutoComplete::widget([
    'name' => 'main-sku',
    'clientOptions' => [
        'source' => array_keys($mainSKUs),
    ],
    'options' =>[
    	'class' => 'form-control search-query',
    	'placeholder' => 'Put the main product SKU',
    	'id' => 'main-sku',

    ],
]);

  ?>
  <span class="input-group-btn">
				<button type="button" class="btn btn-purple btn-sm" id="link-product">
					<span class="ace-icon fa fa-link icon-on-right bigger-110"></span>
					Link
				</button>
			</span>
		</div>
	</div>
</div>



			
