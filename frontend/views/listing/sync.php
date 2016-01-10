<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\bootstrap\Modal;
use frontend\assets\ListingSyncAsset;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\productebaylisting\SearchListing */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app/listing', 'Sync Listings');
$this->params['breadcrumbs'][] = $this->title;
ListingSyncAsset::register($this);
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
          'attribute'=>'Lastest_Sync_Time',
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
              //'show.bs.modal' =>"function(){progreeBarInit();}",
              'shown.bs.modal' => "function(e){preSyncModal(e);}",
              'hidden.bs.modal' => "function(e){closeSyncModal(e);}",
          ],
      ]); ?>
      <div class="notice-board" style='display:none;'>
        <div class="alert">
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
						<div class="infobox-content"><?php echo Yii::t('app/listing', 'No. To Sync', []); ?></div>
						<div class="infobox-content">
              <span id="no-listings"></span>
              <span class="infobox-data-number" id="pre-sync-loading"><?php echo Yii::t('app/listing', 'Connecting...', []); ?><i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i></span>
            </div>
					</div>
        </div>

        <div class="infobox infobox-green infobox-small infobox-dark sync-result" style='display:none;'>
          <div class="infobox-icon">
						<i class="ace-icon fa fa-check-circle"></i>
					</div>
          <div class="infobox-data">
						<div class="infobox-content"><?php echo Yii::t('app/listing', 'No. Success Sync', []); ?></div>
						<div class="infobox-content">
              <span id="no-success-sync">0</span>
            </div>
					</div>
        </div>

        <div class="infobox infobox-red infobox-small infobox-dark sync-result" style='display:none;'>
          <div class="infobox-icon">
						<i class="ace-icon fa fa-exclamation-triangle"></i>
					</div>
          <div class="infobox-data">
						<div class="infobox-content"><?php echo Yii::t('app/listing', 'No. Empty SKUs', []); ?></div>
						<div class="infobox-content">
              <span id="no-empty-sku">0</span>
            </div>
					</div>
        </div>
        <button class="btn btn-warning btn-xlg" id="btn-main-sync" style='display:none;'><?php echo Yii::t('app/listing', 'Sync Now', []); ?></button>

      </div>
      <div class="progress progress-striped" id='sync-progress-bar' style='display:none;'>
				<div class="progress-bar progress-bar-pink active" style="width: 0%" role="progressbar" aria-valuenow="3" aria-valuemin="0" aria-valuemax="100">0%</div>
			</div>
      <div class="" style='display:none;' id="empty-sku-list">
        <h4>Listing ID without SKUs:</h4>
        <div id='sku-scroll' style="max-height: 125px;">
          <ul class="list-unstyled spaced" >
  				</ul>
        </div>

      </div>
      <?php

      ?>
     <?php Modal::end();
   ?>
