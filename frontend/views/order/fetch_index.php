<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\bootstrap\Modal;
use frontend\assets\OrderFetchAsset;
$this->title = Yii::t('app/order', 'Order Fetch Log');
$this->params['breadcrumbs'][] = $this->title;
OrderFetchAsset::register($this);
?>
<button class="btn btn-warning btn-xlg" id="test-time" style=''><?php echo Yii::t('app/order', 'TIme', []); ?></button>
<div class="order-fetch-log">
  <?= GridView::widget([
      'dataProvider' => $dataProvider,
      'columns'=>[
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
        'status',
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
                  'data-target'=>'#fetch-modal']);
            },
          ],
        ],
      ]
  ]); ?>
</div>
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
    <?php

    ?>
   <?php Modal::end();
 ?>
