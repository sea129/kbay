<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

//use yii\grid\GridView;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\orders\EOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
use frontend\models\ebayaccounts\EbayAccount;

$this->title = Yii::t('app/order', 'Eorders');
$this->params['breadcrumbs'][] = $this->title;
?>
<button class="btn btn-warning btn-xlg" id="packing-label" style=''><?php echo Yii::t('app/order', 'Packing Label', []); ?></button>
<?php
  $this->registerJs("$(document).ready(function() {
    $('.get_image').click(function(){
      imgContainer = $(this).parent();
      $(this).hide();
      $.ajax({
        url: '/order/item-pic',
        type: 'POST',
        data: {ebayID:$(this).data('ebayid'),itemID:$(this).data('item'),transactionID:$(this).data('transactionid')}
      })
      .done(function(result) {
        imgContainer.html('<img src='+result+' style=\'max-width:150px;max-height:150px;\' >');
      })
      .fail(function() {
      })
      .always(function() {
      });
    });
  });");
 ?>
<div class="eorder-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'hover'=>true,
        'columns' => [
            [
                'class'=>'kartik\grid\ExpandRowColumn',
                'width'=>'50px',
                'value'=>function ($model, $key, $index, $column) {
                    return GridView::ROW_COLLAPSED;
                },
                'detail'=>function ($model, $key, $index, $column) {
                    return Yii::$app->controller->renderPartial('_expand-transaction', ['model'=>$model]);
                },
                'headerOptions'=>['class'=>'kartik-sheet-style'],
                'expandOneOnly'=>true,
            ],
            [
              'class' => '\kartik\grid\BooleanColumn',
              'attribute' => 'status',
              'trueLabel' => 'YES',
              'falseLabel' => 'NO',
              'label'=>Yii::t('app/order', 'Shipped', []),
              // 'filter'=>[true=>1,false=>0],
            ],
            [
              'attribute'=>'ebay_id',
              'value'=>function($model, $key, $index, $widget){
                return $model->ebay->seller_id;
              },
              'vAlign'=>'middle',
              'width'=>'180px',
              'label'=>Yii::t('app/order', 'eBay Account', []),
              'filterType'=>GridView::FILTER_SELECT2,
              'filter'=>ArrayHelper::map(EbayAccount::find()->where(['user_id'=>Yii::$app->user->id])->orderBy('seller_id')->asArray()->all(), 'id', 'seller_id'),
              'filterWidgetOptions'=>[
                  'pluginOptions'=>['allowClear'=>true],
              ],
              'filterInputOptions'=>['placeholder'=>'eBay Account'],
              'format'=>'raw',
            ],
            'sale_record_number',
            'buyer_id',
            [
              //'attribute'=>'ebayTransactions',
              'label'=>'Buyer Email',
              'value'=>function($model, $key, $index, $column){
                return $model->ebayTransactions[0]->buyer_email;
              },
            ],
            //['class' => 'yii\grid\SerialColumn'],
            //'id',
            //'type',
            // [
            //   'class' => '\kartik\grid\DataColumn',
            //   'attribute'=>'fetched_at',
            //   'label'=>Yii::t('app/order', 'Fetched at', []),
            //   'filterType'=>GridView::FILTER_DATE_RANGE,
            //   'filterWidgetOptions'=>[
            //     'pluginOptions'=>[
            //       //'timePicker'=>true,
            //       //'local'=>['separator'=>' to '],
            //     ],
            //   ],
            // ],

            //'fetched_at',
            //'status',
            //'ebay_id',
            // 'user_id',
             'ebay_order_id',
            // 'ebay_seller_id',
            'total',
            [
              'attribute'=>'created_time',
              'label'=>'Sale Date',
              'filterType'=>GridView::FILTER_DATE_RANGE,
              //'format'=>'date',
              'value'=>function($model, $key, $index, $column){
                if($model['created_time']){
                  $date = new DateTime($model['created_time'],new DateTimeZone('GMT'));
                  $date->setTimezone(new DateTimeZone('Australia/Sydney'));
                  return $date->format('Y-m-d H:i:s');
                }else{
                  return null;
                }

              },
            ],
            [
              'attribute'=>'paid_time',
              'label'=>Yii::t('app/order', 'Paid Time', []),
              'filterType'=>GridView::FILTER_DATE_RANGE,
              //'format'=>'date',
              'value'=>function($model, $key, $index, $column){
                if($model['paid_time']){
                  $date = new DateTime($model['paid_time'],new DateTimeZone('GMT'));
                  $date->setTimezone(new DateTimeZone('Australia/Sydney'));
                  return $date->format('Y-m-d H:i:s');
                }else{
                  return null;
                }

              },
            ],
            [
              'attribute'=>'label',
              'label'=>Yii::t('app/order', 'Labels printed', []),
            ],
            [
              'attribute'=>'shipped_time',
              'label'=>Yii::t('app/order', 'Shipped Time', []),
              'filterType'=>GridView::FILTER_DATE_RANGE,
              //'format'=>'date',
              'value'=>function($model, $key, $index, $column){
                if($model['shipped_time']){
                  $date = new DateTime($model['shipped_time'],new DateTimeZone('GMT'));
                  $date->setTimezone(new DateTimeZone('Australia/Sydney'));
                  return $date->format('Y-m-d H:i:s');
                }else{
                  return null;
                }

              },
            ],
             //'created_time',
            // 'paid_time',
            // 'recipient_name',
            // 'recipient_phone',
            // 'recipient_address1',
            // 'recipient_address2',
            // 'recipient_city',
            // 'recipient_state',
            // 'recipient_postcode',
            // 'checkout_message',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
