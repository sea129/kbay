<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ebayaccounts\EbayAccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app/ebayaccount', 'Ebay Accounts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ebay-account-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app/ebayaccount', 'Create Ebay Account'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'seller_id',
            'store_id',
            //'user_id',
            'shipping_info:ntext',
            // 'warranty_info:ntext',
            // 'payment_info:ntext',
            // 'contact_info:ntext',
            // 'listing_template_id',
            // 'token:ntext',
            // 'email:email',
            // 'listing_assets_url:url',

            ['class' => 'yii\grid\ActionColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('app/ebayaccount','Get Token'),
                'template'=>'{get-token}',
                'buttons' => [
                    'get-token'=>function($url, $model, $key){
                        return Html::a(Html::beginTag('i',['class'=>'ace-icon fa fa-link bigger-120']).Html::endTag('i'),$url,['class'=>'btn btn-xs btn-info','target'=>'_blank']);
                    }
                ],
            ]
        ],
    ]); ?>

</div>
<div>
    <?php $session = Yii::$app->session;

      /*echo '<pre>';
      print_r($session['ebSession']);
      echo '</pre>';
      exit();*/
     ?>
</div>