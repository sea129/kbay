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
              return $model['status']==1?'Init':'Completed';
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
                  //'data-total-pages'=>$model['Not Paid']/$appSetting->number_value,
                  'data-toggle'=>'modal',
                  'data-target'=>'#update-not-paid-orders-modal']);
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
 <div class="download-orders-container" style="max-height:500px;min-height:500px;">
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
    'header' => 'Updating Not Paid Orders',
    'options' =>['id'=>'update-not-paid-orders-modal'],
    'size' => 'modal-lg',
    'clientOptions' => [
        'backdrop' => 'static',
        'keyboard' => false,
    ],
    'clientEvents' =>[
        //'show.bs.modal' =>"function(){progreeBarInit();}",
        'shown.bs.modal' => "function(e){updateNotPaidOrdersInit(e);}",
        'hidden.bs.modal' => "function(e){location.reload();}",
    ],
]); ?>
<div class="update-not-paid-order-container" style="max-height:500px;min-height:500px;">
 <ul class="list-unstyled spaced fa-ul">
   <li>
     <!-- <i class="ace-icon fa fa-check bigger-110 green"></i> -->
     <i class="fa fa-refresh fa-spin fa-fw fa-li fa-lg"></i>
     更新订单中...
   </li>



 </ul>
 <p class="spinner-container">

 </p>
</div>
<?php Modal::end(); ?>
