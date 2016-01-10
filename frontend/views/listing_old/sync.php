<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\productebaylisting\SearchListing */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app/listing', 'Sync Listings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-ebay-listing-sync">
  <?= GridView::widget([
      'dataProvider' => $dataProvider,
      'columns'=>[
        'seller_id',
        [
          'attribute'=>'Number_of_Listings',
          'label'=>Yii::t('app/listing', 'No. Synced Listings', []),
        ],
        [
          'attribute'=>'Lastest_Updated_Time',
          'label'=>Yii::t('app/listing', 'Lastest Updated Time', []),
        ],

        [
          'class'=>'yii\grid\ActionColumn',
          'header'=>Yii::t('app/listing', 'Sync', []),
          'template' => '{sync}',
          'buttons'=>[
            'sync'=>function($url, $model, $key){
              return Html::button('<span class="ace-icon fa fa-refresh"></span>',['type'=>'button','class'=>'btn btn-xs btn-primary btn-pre-sync','data-ebay-id'=>$model['ebay_id'],'data-toggle'=>'modal','data-target'=>'#sync-modal']);
            },
          ],
        ],
      ]
  ]); ?>
</div>
  <?php
      Modal::begin([
          'header' => 'Synchronizing Listing info',
          'options' =>['id'=>'sync-modal'],
          'size' => 'modal-lg',
          'clientOptions' => [
              'backdrop' => 'static',
              'keyboard' => false,
          ],
          'clientEvents' =>[
              //'shown.bs.modal' => "function(){ progressbar(15);}",
          ],
      ]); ?>
      <?php echo "1"; ?>
     <?php Modal::end();
   ?>
