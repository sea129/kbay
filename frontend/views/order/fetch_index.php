<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\bootstrap\Modal;
use frontend\assets\OrderFetchAsset;
$this->title = Yii::t('app/order', 'Order Fetch Log');
$this->params['breadcrumbs'][] = $this->title;
OrderFetchAsset::register($this);
?>
<?php
  echo Html::beginForm(
    'download-label',
    'post',
    ['id'=>'label-form','target'=>'_blank']
  );
?>
<div class="action-buttons">
  <button type="submit" name="label-button" id='batch-label' class='btn btn-s btn-danger' download target="_blank">Batch Labels</button>
</div>
<div class="order-fetch-log">
  <?= GridView::widget([
      'id'=>'main-grid',
      'dataProvider' => $dataProvider,
      'columns'=>[
        [
          'class' => 'yii\grid\CheckboxColumn',
          'checkboxOptions' => function ($model, $key, $index, $column) {
              return ['value' => $model['ebay_id'],'class'=>'check-selection'];
          },
        ],
        'seller_id',
        'order_qty',
        [
          'attribute'=>'create_from',
          'value'=>function($model, $key, $index, $column){
            if($model['create_from']){
              $date = new DateTime($model['create_from'],new DateTimeZone('GMT'));
              $date->setTimezone(new DateTimeZone('Australia/Sydney'));
              return $date->format('Y-m-d H:i:s');
            }else{
              return null;
            }

          },
        ],
        [
          'attribute'=>'create_to',
          'value'=>function($model, $key, $index, $column){
            if($model['create_to']){
              $date = new DateTime($model['create_to'],new DateTimeZone('GMT'));
              $date->setTimezone(new DateTimeZone('Australia/Sydney'));
              return $date->format('Y-m-d H:i:s');
            }else{
              return null;
            }

          },
        ],
        //'create_from',
        //'create_to',
        'complete_at',
        [
          'attribute'=>'status',
          'value'=>function($model, $key, $index, $column){
            if($model['status']){
              return $model['status']==1?'Pre Fetched':'Fetched';
            }else{
              return null;
            }

          }
        ],
        //'status',
        [
          //'attribute'=>'Not Paid',
          'class'=>'yii\grid\ActionColumn',
          'header'=>Yii::t('app/order', 'Not Paid', []),
          'template' => '{update-not-paid}',
          'buttons'=>[
            'update-not-paid'=>function($url, $model, $key){
              $appSetting = \common\models\setting\AppSetting::findOne('fetch_order_entries_per_page');
              return Html::button('<span class="ace-icon fa fa-refresh"></span>'.$model['Not Paid'],
                [
                  'type'=>'button',
                  'class'=>'btn btn-xs btn-warning btn-update-not-paid',
                  'data-ebay-id'=>$model['ebay_id'],
                  'data-total-pages'=>$model['Not Paid']/$appSetting->number_value,
                  'data-toggle'=>'modal',
                  'data-target'=>'#update-modal']);
            },
          ],
        ],
        [
          'attribute'=>'Not Label',
        ],
        [
          'attribute'=>'Not Shipped',
        ],


        [
          'class'=>'yii\grid\ActionColumn',
          'header'=>Yii::t('app/order', 'Get Orders', []),
          'template' => '{pre-fetch}',
          'buttons'=>[
            'pre-fetch'=>function($url, $model, $key){
              return Html::button('<span class="ace-icon fa fa-cart-arrow-down"></span>',
                [
                  'type'=>'button',
                  'class'=>'btn btn-xs btn-danger btn-pre-fetch',
                  'data-ebay-id'=>$model['ebay_id'],
                  //'data-create-to'=>$model['create_to'],
                  'data-toggle'=>'modal',
                  'data-target'=>'#download-orders-modal']);
            },
          ],
        ],
      ]
  ]); ?>
</div>
<?php echo Html::endForm(); ?>
<?php
  Modal::begin([
    'header' => 'Download Orders',
    'options' => ['id'=>'download-orders-modal'],
    'size' => 'modal-lg',
    'clientOptions' => [
        'backdrop' => 'static',
        'keyboard' => false,
    ],
    'clientEvents' =>[
        //'show.bs.modal' =>"function(){progreeBarInit();}",
        'shown.bs.modal' => "function(e){downloadOrdersInit(e);}",
        'hidden.bs.modal' => "function(e){location.reload();}",
    ],
  ]);

 ?>
 <div class="download-order-container" style="max-height:500px;min-height:500px;">
  <ul class="list-unstyled spaced fa-ul">
		<li>
			<!-- <i class="ace-icon fa fa-check bigger-110 green"></i> -->
      <i class="fa fa-refresh fa-spin fa-fw fa-li fa-lg"></i>
			下载订单中...
		</li>



  </ul>
  <p class="spinner-container">

  </p>
 </div>
 <?php Modal::end(); ?>
<?php
    Modal::begin([
        'header' => 'Fetching Orders',
        'options' =>['id'=>'fetch-modal'],
        'size' => 'modal-lg',
        'clientOptions' => [
            'backdrop' => 'static',
            'keyboard' => false,
        ],
        'clientEvents' =>[
            //'show.bs.modal' =>"function(){progreeBarInit();}",
            'shown.bs.modal' => "function(e){preFetchModal(e);}",
            'hidden.bs.modal' => "function(e){closeSyncModal(e);}",
        ],
    ]); ?>
    <div class="notice-board" style='display:none;'>
      <div class="alert alert-block">
        <button type="button" class="close" data-dismiss="alert">
          <i class="ace-icon fa fa-times"></i>
        </button>
        <p class='message'>

        </p>
      </div>
    </div>
    <div class="infobox-container">
      <div class="infobox infobox-grey infobox-small infobox-dark">
        <div class="infobox-icon">
          <i class="ace-icon fa fa-download"></i>
        </div>

        <div class="infobox-data">
          <div class="infobox-content"><?php echo Yii::t('app/order', 'No. To Fetch', []); ?></div>
          <div class="infobox-content">
            <span id="no-orders"></span>
            <span class="infobox-data-number" id="pre-fetch-loading"><?php echo Yii::t('app/order', 'Connecting...', []); ?><i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i></span>
          </div>
        </div>
      </div>

      <button class="btn btn-warning btn-xlg" id="btn-main-fetch" style='display:none;'><?php echo Yii::t('app/order', 'Fetch Now', []); ?></button>
    </div>

    <div class="progress progress-striped" id='fetch-progress-bar' style='display:none;'>
      <div class="progress-bar progress-bar-pink active" style="width: 0%" role="progressbar" aria-valuenow="3" aria-valuemin="0" aria-valuemax="100">0%</div>
    </div>
    <div class="" style='display:none;' id="saving-error">
      <div class="alert alert-block alert-success">
        <p class="">
          Completed!
        </p>
      </div>
      <div id='error-scroll' style="max-height: 125px;">
        <ul class="list-unstyled spaced" >
        </ul>
      </div>

    </div>
    <?php

    ?>
   <?php Modal::end();
 ?>
<?php
Modal::begin([
    'header' => 'Updating Not Paid Orders',
    'options' =>['id'=>'update-modal'],
    'size' => 'modal-lg',
    'clientOptions' => [
        'backdrop' => 'static',
        'keyboard' => false,
    ],
    'clientEvents' =>[
        //'show.bs.modal' =>"function(){progreeBarInit();}",
        'shown.bs.modal' => "function(e){updateNotPaid(e);}",
        'hidden.bs.modal' => "function(e){location.reload();}",
    ],
]); ?>
<div class="notice-board" style='display:none;'>
  <div class="alert alert-block">
    <button type="button" class="close" data-dismiss="alert">
      <i class="ace-icon fa fa-times"></i>
    </button>
    <p class='message'>

    </p>
  </div>
</div>
<div class="">

  <p style='text-align:center;'>

    <i class="ace-icon fa fa-spinner fa-spin orange bigger-275" style=''></i>
  </p>

</div>

<div class="" style='display:none;' id="saving-error">
  <div class="alert alert-block alert-success">
    <p class="">
      Completed!
    </p>
  </div>
  <div id='error-scroll' style="max-height: 125px;">
    <ul class="list-unstyled spaced" >
    </ul>
  </div>

</div>
<?php

?>
<?php Modal::end();
 ?>
