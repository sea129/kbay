<?php

use yii\helpers\Html;
//use yii\widgets\DetailView;
use kartik\detail\DetailView;
use yii\helpers\Url;
use frontend\assets\ProductViewAsset;
use kartik\file\FileInput;
use kartik\sortable\Sortable;
use yii\widgets\Pjax;

use yii\bootstrap\Modal;
/* @var $this yii\web\View */
/* @var $model frontend\models\products\Product */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/product', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
ProductViewAsset::register($this);
?>
<div class="product-view row">
    <p>
        <?= Html::a(Yii::t('app/product', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app/product', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app/product', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php
    $track = $model->is_trackable==1?Yii::t('app/product', 'trackable'):Yii::t('app/product', 'untrack');
    $attributes = [
        [
            'group' => true,
            'label' => Yii::t('app/product', 'Product Image').'&nbsp;<a href="#" data-toggle="col-image" class="col-toggel"><i class="ace-icon fa fa-chevron-up"></i></a>',
            'rowOptions' => ['class'=>'info'],
            'groupOptions' => ['style'=>'text-align:center;'],
        ],
        [
            'columns'=>[

                [
                    'attribute' => 'main_image',
                    'format'=>'html',
                    'value'=>"<img src='".$model->main_image."' width='200px'>",
                    'labelColOptions'=>['style' => 'width: 10%;display:none;'],
                    'valueColOptions'=>['id'=>'col-image','style' => 'display:none;'],
                  ],
                ],
        ],
        [
            'group' => true,
            'label' => Yii::t('app/product', 'Stock info'),
            'rowOptions' => ['class'=>'info'],
        ],
        [
            'columns'=>[
                [
                    'attribute' => 'sku',
                    'label'=>'SKU',
                ],
                [
                    'attribute' => 'category_id',
                    'value'=>$model->category->name,
                ],
                [
                    'attribute' => 'supplier_id',
                    'value' => $model->supplier->name,
                ],
              ],

        ],
        [
            'columns'=>[
                [
                    'attribute' => 'name',
                ],
                [
                    'attribute' => 'cost',
                    'format'=>'raw',
                    'value' => '<span class="label label-info label-sm" style="font-size:20px;height:28px;">'.'<i class="fa fa-usd"></i>&nbsp;'.$model->cost.'</span>',
                ],
            ]
        ],

        [
            'columns' => [
                [
                    'attribute' => 'stock_qty',
                    'format'=>'raw',
                    'value' => '<span class="label label-info label-sm" style="font-size:20px;height:28px;">'.$model->stock_qty.'</span>',
                ],
                [
                    'attribute' => 'stock_location',
                    'format'=>'raw',
                    'value' => '<span class="label label-info label-sm" style="font-size:20px;height:28px;">'.$model->stockLocation->code.'</span>',
                ],
                [
                    'attribute' => 'qty_per_order',
                    'format'=>'raw',
                    'value' => '<span class="label label-warning label-sm" style="font-size:20px;height:28px;">'.$model->qty_per_order.'</span>',
                ],
            ],
        ],
        [
            'columns' => [
                [
                    'attribute' => 'weight',
                    'format'=>'raw',
                    'value' => '<span class="label label-info label-sm" style="font-size:20px;height:28px;">'.$model->weight.'<i class="ace-icon white">g</i>'.'</span>',
                ],
                [
                    'attribute' => 'is_trackable',
                    'format'=>'raw',
                    'value' => '<span class="label '.($model->is_trackable==1?'label-success':'label-info').' label-sm" style="font-size:20px;height:28px;">'.$track.'</span>',
                ],
            ],
        ],
        [
            'group' => true,
            'label' => Yii::t('app/product', 'Mini Desc').'&nbsp;<a href="#" data-toggle="col-mini-desc" class="col-toggel"><i class="ace-icon fa fa-chevron-up"></i></a>',
            'rowOptions' => ['class'=>'info'],
            'groupOptions' => ['style'=>'text-align:center;'],
        ],
        [
            'columns' => [
                [
                    'attribute' => 'mini_desc',
                    'format'=>'raw',
                    'labelColOptions'=>['style' => 'width: 10%;display:none;'],
                    'valueColOptions'=>['id'=>'col-mini-desc','style' => 'display:none;'],
                ],
              ],
        ],
        [
            'group' => true,
            'label' => Yii::t('app/product', 'Description').'&nbsp;<a href="#" data-toggle="col-description" class="col-toggel"><i class="ace-icon fa fa-chevron-up"></i></a>',
            'rowOptions' => ['class'=>'info'],
            'groupOptions' => ['style'=>'text-align:center;']
        ],
        [
            'columns' => [
                [
                    'attribute' => 'description',
                    'format'=>'raw',
                    'labelColOptions'=>['style' => 'width: 10%;display:none;'],
                    'valueColOptions'=>['id'=>'col-description','style' => 'display:none;'],
                ],
            ],
        ],
        [
            'group' => true,
            'label' => Yii::t('app/product', 'Specs').'&nbsp;<a href="#" data-toggle="col-specs" class="col-toggel"><i class="ace-icon fa fa-chevron-up"></i></a>',
            'rowOptions' => ['class'=>'info'],
            'groupOptions' => ['style'=>'text-align:center;'],

        ],
        [
            'columns' => [
                [
                    'attribute' => 'specs',
                    'format'=>'raw',
                    'labelColOptions'=>['style' => 'width: 10%;display:none;'],
                    'valueColOptions'=>['id'=>'col-specs','style' => 'display:none;'],
                ],
            ],
        ],
        [
            'group' => true,
            'label' => Yii::t('app/product', 'Comment'),
            'rowOptions' => ['class'=>'info'],
            'groupOptions' => ['style'=>'text-align:center;'],
        ],
        [
            'columns' => [
                [
                    'attribute' => 'comment',
                    'format'=>'raw',
                    'labelColOptions'=>['style' => 'width: 10%;display:none;'],
                ],
            ],
        ],
    ];
    echo DetailView::widget([
            'model' => $model,
            'attributes' => $attributes,
            'mode' => 'view',
            'bordered' => true,
            'striped' =>true,
            'condensed' => true,
            'responsive' => true,
            'hover' => true,
            'hAlign'=>'right',
            'vAlign'=>'middle',
        ]);
     ?>
</div>
<div class="row">
<?php
  $ebayAccArray = $model->getEbayAccoutsObj();
  $listingImages = $model->getEbayLstImgs();
  $sortableItems = $model->formSortableItems($listingImages);

  // echo $listings;
   echo '<pre>';
   //var_dump($sortableItems);
   echo '</pre>';
?>
<?php
  // Pjax::begin(['id' => 'test','timeout' => 7000,'linkSelector'=>'#tt']);
  // echo "asdfasdfasdfasdf";echo "<br/>";
   ?>
   <!-- <button type='submit' id='tt' >Create Listing</button> -->

  <?php  //Pjax::end();
 ?>
<?php foreach ($ebayAccArray as $key => $ebayAccountObj) { ?>
    <div class="col-xs-12 col-sm-3 widget-container-col ui-sortable" id="listing-info-<?php echo $ebayAccountObj->id; ?>">
        <div class="widget-box ui-sortable-handle">
            <div class="widget-header">
                <h5 class="widget-title">
                    <?php echo $ebayAccountObj->seller_id; ?>
                </h5>
                <div class="widget-toolbar">
                    <a href="#" data-action="collapse">
                        <i class="ace-icon fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="widget-body" style="display:block">
                <div class="widget-main">
                  <?php Pjax::begin(['timeout' => 5000,'id' => 'listing-images-pjax-'.$ebayAccountObj->id]); ?>

                  <div class="listing-images-loading" style='display:none;text-align:center;'>
                    <img src="<?php echo Url::to(['/images/loading.gif']) ?>" alt="" />
                  </div>
                  <div class="listing-images-wrap">
                    <?php
                      echo Sortable::widget([
                        'type'=>'grid',
                        //'items' => isset($sortableItems[$ebayAccountObj->id])?$sortableItems[$ebayAccountObj->id]:[],
                        'items' => isset($sortableItems[$ebayAccountObj->id])?$sortableItems[$ebayAccountObj->id]:[],
                        'itemOptions' =>[
                          'class'=>'sortable-item',
                        ],
                        'options'=>['id'=>'sortable-'.$ebayAccountObj->id],
                        'pluginEvents'=>[
                        ],
                      ]);
                     ?>
                  </div>
                  <?php $maxImageCount = isset($sortableItems[$ebayAccountObj->id])? 8-count($sortableItems[$ebayAccountObj->id]):8; ?>

                    <div style="margin-bottom:5px;<?php if($maxImageCount==0){ echo "display:none;"; } ?>">

                    <p>Upload Images - <?php echo $maxImageCount ?> more images MAX size 200kb</p>

                    <?php echo FileInput::widget([
                                    'name' =>'pimages[]',
                                    'options' => ['id'=>'upload-listing-images-'.$key,'multiple' => true,'accept' => 'image/*'],
                                    'pluginOptions' => [
                                      // 'layoutTemplates'=>[
                                      //   'footer'=>'',
                                      //   'actions'=>'',
                                      // ],
                                        // 'showCaption'=>false,
                                        // 'showRemove'=>false,
                                        // 'showUpload'=>false,
                                        // 'showCancel'=>false,
                                        //'showPreview'=>false,
                                        'previewSettings'=>[
                                          'image'=>[
                                            'width'=>'100px','height'=>'auto',
                                          ],
                                        ],
                                        'maxFileSize'=>'2000',
                                        'maxFileCount'=>$maxImageCount,
                                        'uploadAsync'=>false,
                                        'showCancel'=>false,
                                        'uploadUrl' => Url::to(['product/upload-list-image']),
                                        //'uploadUrl' => Url::to('http://uploads.im/api'),
                                        'uploadExtraData'=>['ebayID'=>$ebayAccountObj->id,'productID'=>$model->id],
                                        'allowedFileTypes'=>['image'],
                                        'allowedFileExtensions'=>['jpg','png'],
                                        'browseLabel'=>'',
                                        'browseClass'=>'btn btn-sm btn-pink',
                                        'removeClass' => 'btn btn-sm btn-pink',
                                        'removeLabel'=> '',
                                        'cancelLabel'=>'',
                                        'uploadLabel'=>'',
                                        'cancelClass'=>'btn btn-sm btn-pink',
                                        'uploadClass'=>'btn btn-sm btn-pink',
                                        'browseClass' => 'btn btn-success btn-sm',
                                        'uploadClass' => 'btn btn-danger btn-sm',
                                        'removeClass' => 'btn btn-info btn-sm',
                                    ],

                                    'pluginEvents'=>[
                                        // 'filebatchpreupload'=>"function(event, data, previewId, index){
                                        //     data.jqXHR.abort();
                                        //     listingImageInit();
                                        //     var fileInput = $(this).parents('.file-input');
                                        //     uploadAjaxHack(0,data.files,fileInput.find('.progress-bar'));
                                        //     deferredUpload = $.Deferred();
                                        //     deferredUpload.done(function(value){
                                        //       if(value){
                                        //         saveListingImagesInfo(".$ebayAccountObj->id.",".$model->id.".);
                                        //         deferredSave = $.Deferred().done(function(value){
                                        //           if(value){
                                        //             fileInput.find('.progress-bar').html('100%').css('width','100%');
                                        //           }else{
                                        //             fileInput.find('.progress-bar').html('Error').css('width','100%').toggleClass('progress-bar-success').toggleClass('progress-bar-danger');
                                        //           }
                                        //         });
                                        //       }else{
                                        //         fileInput.find('.progress-bar').html('Error').css('width','100%').toggleClass('progress-bar-success').toggleClass('progress-bar-danger');
                                        //       }
                                        //     });
                                        // }",
                                        // 'fileuploaded'=>'function(event, data, previewId, index){
                                        //   uploadComplete(data.response);
                                        // }',
                                        'filebatchuploadcomplete' => "function(event, files, extra){pjaxReload(".$ebayAccountObj->id.")}",
                                    ],

                                ]); ?>
                    </div>
                    <?php Pjax::end(); ?>
                    <button type='button' class="btn btn-white btn-yellow btn-sm btn-block serializeImgs" data-product-id="<?php echo $model->id; ?>" data-ebay-id="<?php echo $ebayAccountObj->id; ?>">save images</button>
                    <a target='_blank' href='<?php echo Url::to(['product/preview-desc','id'=>$model->id,'ebayID'=>$ebayAccountObj->id,]); ?>' class="btn btn-white btn-yellow btn-sm btn-block">Preview Template</a>
                    <a target='_blank' href='<?php echo Url::to(['product/generate-code','id'=>$model->id,'ebayID'=>$ebayAccountObj->id,]); ?>' class="btn btn-white btn-yellow btn-sm btn-block">Generate Code</a>

                    <?php if(isset($listings[$ebayAccountObj->id])){ ?>
                    <button type='button' class="btn btn-white btn-yellow btn-sm btn-block revise-listing" data-toggle='modal' data-target='#revise-listing-modal' data-ebay-id='<?php echo $ebayAccountObj->id; ?>' data-ebay-seller='<?php echo $ebayAccountObj->seller_id; ?>' data-item-id='<?php echo $listings[$ebayAccountObj->id]['item_id']; ?>'>Revise Listing</button>
                    <div class="listing-selling-info">
                      <h6><?php echo $listings[$ebayAccountObj->id]['title']; ?></h6>
                      <ul class="list-unstyled spaced">
                        <li>
													<i class="ace-icon fa fa-clock-o green"></i>
													<?php echo $listings[$ebayAccountObj->id]['sync_at']; ?>
												</li>
                        <li>
													<i class="ace-icon fa fa-barcode green"></i>
													<?php echo $listings[$ebayAccountObj->id]['item_id']; ?>
												</li>
                        <li>
													<i class="ace-icon fa fa-usd green"></i>
													<?php echo $listings[$ebayAccountObj->id]['price']; ?>
												</li>
                        <li>
													<i class="ace-icon fa fa-database green"></i>
													<?php echo $listings[$ebayAccountObj->id]['qty']; ?> available / <?php echo $listings[$ebayAccountObj->id]['sold_qty']; ?> sold
												</li>
                      </ul>
                    </div>
                    <?php }else{ ?>
                    <button type='button' class="btn btn-white btn-yellow btn-sm btn-block create-listing" data-toggle='modal' data-target='#add-listing-modal' data-ebay-id='<?php echo $ebayAccountObj->id; ?>' data-ebay-seller='<?php echo $ebayAccountObj->seller_id; ?>' >Create Listing</button>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
</div>
<?php
    Modal::begin([
        'header' => 'Add a Listing',
        'options' =>['id'=>'add-listing-modal'],
        'size' => 'modal-lg',
        'clientOptions' => [
            'backdrop' => 'static',
            'keyboard' => false,
        ],
        'clientEvents' =>[
            'show.bs.modal' =>"function(e){showSellerId(e);initCreateModal(e);}",
            //'shown.bs.modal' => "function(e){preSyncModal(e);}",
            //'hidden.bs.modal' => "function(e){closeSyncModal(e);}",
        ],
    ]); ?>
    <div class="row">
      <div class="col-xs-8">
        <div class="input-group">
    			<span class="input-group-addon">
    				<i class="ace-icon fa fa-check"></i>
    			</span>

    			<input type="text" class="form-control" placeholder="Type a similar item ID" id='similar-item-id'>
    			<span class="input-group-btn">
    				<button type="button" class="btn btn-purple btn-sm" id="similar-search">
    					<span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
    					Search
    				</button>
    			</span>
    		</div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-8">
        <h3>eBay Seller: <span id='ebay-seller-id'></span></h3>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <div class="notice-board" style='display:none;'>
          <div class="alert">
  					<button type="button" class="close" data-dismiss="alert">
  						<i class="ace-icon fa fa-times"></i>
  					</button>
  					<p class='message'>

  					</p>
  				</div>
        </div>
      </div>
    </div>
    <div class="row" style='display:none;text-align:center;' id="similar-search-loading">
      <img src="<?php echo Url::to(['/images/loading.gif']) ?>" alt="" />
    </div>
    <div class="row" id='listing-details'>

      <div class="col-xs-12">
        <h3>Listing Details</h3>
        <form class="form-horizontal" role="form">
          <input type="hidden" id="ebay-id" class="col-xs-12">
          <input type="hidden" id="product-id" class="col-xs-12" value="<?php echo $model->id; ?>">
          <div class="form-group">
            <label for="item-cate-id" class="col-xs-2 control-label no-padding-right">
              eBay Category ID
            </label>
            <div class="col-xs-3">
              <input type="text" id="item-cate-id" class="col-xs-12">
            </div>
          </div>
          <div class="form-group">
            <label for="item-title" class="col-xs-2 control-label no-padding-right">
              Item Title
            </label>
            <div class="col-xs-10">
              <input type="text" id="item-title" class="col-xs-12" maxlength="80">
            </div>
          </div>
          <div class="form-group">
            <label for="item-price" class="col-xs-2 control-label no-padding-right">
              Item Price
            </label>
            <div class="col-xs-3">
              <span class="input-icon">
              <input type="text" id="item-price" class="col-xs-12"><i class="ace-icon bigger-110 fa fa-usd blue"></i>
              </span>

            </div>
          </div>
          <div class="form-group">
            <label for="best-offer-switch" class="col-xs-2 control-label no-padding-right">
              Best Offer
            </label>
            <div class="col-xs-3">
								<input id="best-offer-switch" class="ace ace-switch ace-switch-6" type="checkbox">
								<span class="lbl"></span>
            </div>
          </div>
          <div class="form-group" id="best-offer-price" style="display:none;">
            <label for="accept-price" class="col-xs-2 control-label no-padding-right">
              Auto accept price
            </label>
            <div class="col-xs-3">
              <span class="input-icon">
                <input type="text" id="accept-price" class="col-xs-12"><i class="ace-icon bigger-110 fa fa-usd blue"></i>
              </span>
            </div>
            <label for="decline-price" class="col-xs-2 control-label no-padding-right">
              Auto decline price
            </label>
            <div class="col-xs-3">
              <span class="input-icon">
                <input type="text" id="decline-price" class="col-xs-12"><i class="ace-icon bigger-110 fa fa-usd blue"></i>
              </span>
            </div>
          </div>
          <div class="form-group">
            <label for="item-qty" class="col-xs-2 control-label no-padding-right">
              Qty to List
            </label>
            <div class="col-xs-3">
              <input type="text" id="item-qty" class="col-xs-12" value="1">
            </div>
          </div>
          <div class="form-group">
            <label for="shipping-serivce" class="col-xs-2 control-label no-padding-right">
              Shipping Service
            </label>
            <div class="col-xs-3">
              <select class="form-control" id="shipping-serivce">
  							<option value="AU_Regular">AU_Regular</option>
  							<option value="AU_Registered">AU_Registered</option>
  							<option value="AU_RegularParcelWithTracking">AU_RegularParcelWithTracking</option>
  							<option value="AU_RegularParcelWithTrackingAndSignature">AU_RegularParcelWithTrackingAndSignature</option>
  							<option value="AU_StandardDelivery">AU_StandardDelivery</option>
  						</select>
            </div>
          </div>
          <div class="form-group">
            <label for="free-shipping-switch" class="col-xs-2 control-label no-padding-right">
              Free Shipping
            </label>
            <div class="col-xs-3">
								<input id="free-shipping-switch" class="ace ace-switch ace-switch-6" type="checkbox" checked>
								<span class="lbl"></span>
            </div>
          </div>
          <div class="form-group" id="shipping-cost" style="display:none;">
            <label for="shipping-cost-one" class="col-xs-2 control-label no-padding-right">
              Shipping Cost
            </label>
            <div class="col-xs-3">
              <span class="input-icon">
                <input type="text" id="shipping-cost-one" class="col-xs-12"><i class="ace-icon bigger-110 fa fa-usd blue"></i>
              </span>
            </div>
            <label for="shipping-cost-addition" class="col-xs-2 control-label no-padding-right">
              Additional item Cost
            </label>
            <div class="col-xs-3">
              <span class="input-icon">
                <input type="text" id="shipping-cost-addition" class="col-xs-12"><i class="ace-icon bigger-110 fa fa-usd blue"></i>
              </span>
            </div>
          </div>
          <div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-info" type="button" id="btn-add-listing-submit" style="">
								<i class="ace-icon fa fa-check bigger-110"></i>
								Submit
							</button>
              <button class="btn btn-info" type="button" id="btn-add-listing-verify" style="display:none;">
								<i class="ace-icon fa fa-check bigger-110"></i>
								Verify
							</button>
							&nbsp; &nbsp; &nbsp;
							<!-- <button class="btn" type="reset">
								<i class="ace-icon fa fa-undo bigger-110"></i>
								Reset
							</button> -->
						</div>
					</div>
        </form>

      </div>
    </div>
<?php Modal::end(); ?>
<?php
    Modal::begin([
        'header' => 'Revise a Listing',
        'options' =>['id'=>'revise-listing-modal'],
        'size' => 'modal-lg',
        'clientOptions' => [
            'backdrop' => 'static',
            'keyboard' => false,
        ],
        'clientEvents' =>[
            'show.bs.modal' =>"function(e){showSellerId(e);initReviseModal(e);}",
            //'shown.bs.modal' => "function(e){preSyncModal(e);}",
            //'hidden.bs.modal' => "function(e){closeSyncModal(e);}",
        ],
    ]); ?>
    <div class="row">
      <div class="col-xs-8">
        <h3>eBay Seller: <span id='ebay-seller-id'></span></h3>
        <h4>Item ID: <span id='item-id-fill'></span></h4>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <div class="notice-board" style='display:none;'>
          <div class="alert">
            <button type="button" class="close" data-dismiss="alert">
              <i class="ace-icon fa fa-times"></i>
            </button>
            <p class='message'>

            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="row" style='display:none;text-align:center;' id="revise-loading">
      <img src="<?php echo Url::to(['/images/loading.gif']) ?>" alt="" />
    </div>

    <div class="row" id="revise-form">
      <div class="col-xs-12">
        <h3>Revise listing details</h3>
        <form class="form-horizontal" role="form">
          <input type="hidden" id="ebay-id-revise" class="col-xs-12">

          <div class="form-group">
            <label for="item-title" class="col-xs-2 control-label no-padding-right">
              Item Title
            </label>
            <div class="col-xs-10">
              <input type="text" id="item-title-revise" class="col-xs-12" maxlength="80">
            </div>
          </div>
          <div class="form-group">
            <label for="item-price" class="col-xs-2 control-label no-padding-right">
              Item Price
            </label>
            <div class="col-xs-3">
              <span class="input-icon">
              <input type="text" id="item-price-revise" class="col-xs-12"><i class="ace-icon bigger-110 fa fa-usd blue"></i>
              </span>

            </div>
          </div>
          <div class="form-group">
            <label for="item-qty" class="col-xs-2 control-label no-padding-right">
              Qty to List
            </label>
            <div class="col-xs-3">
              <input type="text" id="item-qty-revise" class="col-xs-12">
            </div>
          </div>
          <div class="form-group">
            <label for="free-shipping-switch" class="col-xs-2 control-label no-padding-right">
              Free Shipping
            </label>
            <div class="col-xs-3">
								<input id="free-shipping-switch-revise" class="ace ace-switch ace-switch-6" type="checkbox" checked>
								<span class="lbl"></span>
            </div>
          </div>
          <div class="form-group">
            <label for="shipping-serivce" class="col-xs-2 control-label no-padding-right">
              Shipping Service
            </label>
            <div class="col-xs-3">
              <select class="form-control" id="shipping-serivce">
  							<option value="AU_Regular">AU_Regular</option>
  							<option value="AU_Registered">AU_Registered</option>
  							<option value="AU_RegularParcelWithTracking">AU_RegularParcelWithTracking</option>
  							<option value="AU_RegularParcelWithTrackingAndSignature">AU_RegularParcelWithTrackingAndSignature</option>
  							<option value="AU_StandardDelivery">AU_StandardDelivery</option>
  						</select>
            </div>
          </div>
          <div class="form-group" id="shipping-cost-revise" style="display:none;">
            <label for="shipping-cost-one" class="col-xs-2 control-label no-padding-right">
              Shipping Cost
            </label>
            <div class="col-xs-3">
              <span class="input-icon">
                <input type="text" id="shipping-cost-one-revise" class="col-xs-12"><i class="ace-icon bigger-110 fa fa-usd blue"></i>
              </span>
            </div>
            <label for="shipping-cost-addition" class="col-xs-2 control-label no-padding-right">
              Additional item Cost
            </label>
            <div class="col-xs-3">
              <span class="input-icon">
                <input type="text" id="shipping-cost-addition-revise" class="col-xs-12"><i class="ace-icon bigger-110 fa fa-usd blue"></i>
              </span>
            </div>
          </div>
          <div class="form-group">
            <label for="express-shipping-switch" class="col-xs-2 control-label no-padding-right">
              Express Postage
            </label>
            <div class="col-xs-3">
								<input id="express-shipping-switch-revise" class="ace ace-switch ace-switch-6" type="checkbox" >
								<span class="lbl"></span>
            </div>
          </div>
          <div class="form-group" id="shipping-cost-express-revise" style="display:none;">
            <label for="shipping-cost-express-one-revise" class="col-xs-2 control-label no-padding-right">
              Shipping Express Cost
            </label>
            <div class="col-xs-3">
              <span class="input-icon">
                <input type="text" id="shipping-express-cost-one-revise" class="col-xs-12"><i class="ace-icon bigger-110 fa fa-usd blue"></i>
              </span>
            </div>
            <label for="shipping-express-cost-addition-revise" class="col-xs-2 control-label no-padding-right">
              Additional item Express Cost
            </label>
            <div class="col-xs-3">
              <span class="input-icon">
                <input type="text" id="shipping-express-cost-addition-revise" class="col-xs-12"><i class="ace-icon bigger-110 fa fa-usd blue"></i>
              </span>
            </div>
          </div>
          <div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-info" type="button" id="btn-revise-listing-submit" style="">
								<i class="ace-icon fa fa-check bigger-110"></i>
								Submit
							</button>
							&nbsp; &nbsp; &nbsp;
							<!-- <button class="btn" type="reset">
								<i class="ace-icon fa fa-undo bigger-110"></i>
								Reset
							</button> -->
						</div>
					</div>
        </form>

      </div>
    </div>
<?php Modal::end(); ?>
