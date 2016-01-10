<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\ebayaccounts\EbayAccount */
use yii\helpers\Url;
use frontend\assets\EbayAccountAsset;
use yii\bootstrap\Modal;
use yii\web\View;

$this->title = $model->seller_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/ebayaccount', 'Ebay Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="ebay-account-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app/ebayaccount', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app/ebayaccount', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app/ebayaccount', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            ['attribute'=>'id','visible'=>false],
            'seller_id',
            'store_id',
            //'user_id',
            'shipping_info:ntext',
            'warranty_info:ntext',
            'payment_info:ntext',
            'contact_info:ntext',
            'listing_template_id',
            'token:ntext',
            'token_expiration:date',
            'email:email',
            'listing_assets_url:url',
        ],
    ]) ?>

</div>


<?php
    $this->registerCss(".infobox{
        width:280px;
    }");
    $this->registerJs('var id='.$model->id.';',View::POS_HEAD);
    EbayAccountAsset::register($this);
 ?>

<div class="row">
    <div class="col-xs-12 col-sm-12 widget-container-col ui-sortable">
        <div class="widget-box ui-sortable-handle">
            <div class="widget-header">
                <h5 class="widget-title">
                    <?php echo Yii::t('app/ebayaccount','Sync Listing - Product Information'); ?>
                </h5>
                <div class="widget-toolbar">
                    <a href="#" data-action="collapse">
                        <i class="ace-icon fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="widget-body" style="display:block">
                <div class="widget-main">
                    <div class="infobox-container">
                        <div class="infobox infobox-green">
                            <div class="infobox-icon">
                                <i class="ace-icon fa fa-refresh"></i>
                            </div>
                            <div class="infobox-data">
                                <span class="infobox-data-number">
                                    <?php echo $infoSize = sizeof($synListingInfo); ?>
                                </span>
                                <div class="infobox-content">
                                    NO. of sync listings
                                </div>
                            </div>
                        </div>
                        <div class="infobox infobox-blue">
                            <div class="infobox-icon">
                                <i class="ace-icon fa fa-clock-o"></i>
                            </div>

                            <div class="infobox-data">
                                <span class="infobox-data-number"><?php echo $infoSize<=0?"No records":$synListingInfo['0']->updated_at; ?></span>
                                <div class="infobox-content">Last Sync Time</div>
                            </div>
                        </div>
                        <div class="infobox infobox-red">
                            <button class="btn btn-lg btn-success" id='sync-button' data-toggle="modal" data-target="#progress">
                                <i class="ace-icon fa fa-refresh"></i>
                                Sync Now
                            </button>>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<?php
    Modal::begin([
        'header' => 'Synchronizing - Getting Listing info from Ebay Account : <h4 class="smaller lighter red">'.$model->seller_id.'</h4>',
        'options' =>['id'=>'progress'],
        'size' => 'modal-lg',
        'clientOptions' => [
            'backdrop' => 'static',
            'keyboard' => false,
        ],
        'clientEvents' =>[
            'shown.bs.modal' => "function(){ progressbar(15);}",
        ],
    ]); ?>

    <div class="progress progress-striped active" >
        <div class="progress-bar progress-bar-yellow" style="width: 5%" id="ajax-progress"></div>
    </div>
    <div class="row">
        <div class='col-sm-4'>
            <div class="infobox infobox-grey infobox-large infobox-dark">
                <div class="infobox-icon">
                    <i class="ace-icon fa fa-download"></i>
                </div>

                <div class="infobox-data">
                    <div class="infobox-content">Added Listing NO.</div>
                    <span class="infobox-data-number" id="added-listings"><i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i></span>
                </div>
            </div>
            <div class="widget-box">
                <div class="widget-body" id="added-sku">
                    <ul class="list-unstyled">

                    </ul>
                </div>
            </div>
        </div>
        <div class='col-sm-4'>
            <div class="infobox infobox-blue infobox-large infobox-dark">
                <div class="infobox-icon">
                    <i class="ace-icon fa fa-exchange"></i>
                </div>
                <div class="infobox-data">
                    <div class="infobox-content">Updated Listings NO.</div>
                    <span class="infobox-data-number" id="updated-listings"><i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i></span>
                </div>
            </div>
            <div class="widget-box">
                <div class="widget-body" id="updated-sku">
                    <ul class="list-unstyled">

                    </ul>
                </div>
            </div>
        </div>
        <div class='col-sm-4'>
            <div class="infobox infobox-green infobox-large infobox-dark">
                <div class="infobox-icon">
                    <i class="ace-icon fa fa-exclamation-triangle"></i>
                </div>

                <div class="infobox-data">
                    <div class="infobox-content">Deleted Listings NO.</div>
                    <span class="infobox-data-number" id="deleted-listings"><i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i></span>
                </div>
            </div>
            <div class="widget-box">
                <div class="widget-body" id="deleted-sku">
                    <ul class="list-unstyled">

                    </ul>
                </div>
            </div>
        </div>
    </div>
   <?php Modal::end();
 ?>
