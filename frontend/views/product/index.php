<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\products\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app/product', 'Products');
$this->params['breadcrumbs'][] = $this->title;
$ebayAccounts = $searchModel->getEbayAccouts();
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app/product', 'Create Product'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            // [
            //   'attribute'=>'main_image',
            //   'format'=>['image','options'=>['width'=>'100px']],
            // ],
            [
              'attribute'=>'main_image',
              'value'=>function($model, $key, $index, $column){
                return Html::a(Html::img($model->main_image,['width'=>"100"]),Url::to(['view','id'=>$model->id]));
              },
              'format'=>'raw',
            ],
            // [
            //   'attribute'=>'main_image',
            //   'value'=>function($model, $key, $index, $column){$url = explode('/',$model->main_image);array_splice($url,-1,0,'t');return implode('/',$url);},
            //   'format'=>['image','options'=>['width'=>'100px']],
            // ],
            // 'id',
            [
              'attribute'=>'sku',
              'value'=>function($model, $key, $index, $widget){
                return Html::a($model->sku,Url::to(['view','id'=>$model->id]));
              },
              'format'=>'raw',
            ],
            'name',
            // 'mini_desc:ntext',
             'stock_qty',
             'cost',
            // 'description:ntext',
            // 'specs:ntext',
            // [
            //   'label'=>Yii::t('app/product', 'Category ID'),
            //   'attribute'=>'category.name',
            // ],
            // [
            //   'label'=>Yii::t('app/product', 'Supplier ID'),
            //   'attribute'=>'supplier.name',
            // ],
            // 'user_id',
             'stock_location',

            // 'packaging_id',
             'weight',
            // 'is_trackable',
            // 'comment:ntext',
            // 'qty_per_order',
            /*[
                'class' => 'yii\grid\CheckboxColumn',
            ],*/
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{update}{delete}{add-batch-product}',
                'buttons' => [
                    'view' => function($url, $model, $key){
                        return Html::a('<span class="ace-icon fa fa-eye"></span>',$url,['class'=>'btn btn-xs btn-primary']);
                    },
                    'delete' => function($url, $model, $key){
                        return Html::a('<span class="ace-icon fa fa-trash-o"></span>',$url,['class'=>'btn btn-xs btn-danger','data-method'=>'post','data-confirm'=>Yii::t('yii', 'Are you sure you want to delete this item?')]);
                    },
                    'add-batch-product' => function($url, $model, $key){
                        if($model->qty_per_order===1){
                            return Html::a('<span class="ace-icon fa fa-plus"></span>',Url::to(['product/add-batch-product','mainID'=>$key]),['class'=>'btn btn-xs btn-warning']);
                        }else{

                        }
                    },
                    'update'=>function($url,$model,$key){
                        if($model->stock_qty===null){
                            return Html::a('<span class="ace-icon fa fa-pencil"></span>',Url::to(['product/update-batch-product','id'=>$key]),['class'=>'btn btn-xs btn-info']);
                        }else{
                            return Html::a('<span class="ace-icon fa fa-pencil"></span>',$url,['class'=>'btn btn-xs btn-info']);
                        }
                    },
                ]
            ],
            // [
            //     'class' => 'yii\grid\ActionColumn',
            //     'header'=> Yii::t('app/product','Preview'),
            //     'template'   => '{preview-desc}',
            //     'buttons' => ['preview-desc' => function($url, $model, $key) use ($ebayAccounts){
            //             $result = Html::beginForm($url,'post',['target'=>'_blank']);
            //             $result .= Html::beginTag('div',['class'=>'input-group']);
            //             $result .= Html::dropDownList('ebayAccID',null,$ebayAccounts,['class'=>'form-control']);
            //             $result .= Html::beginTag('span',['class'=>'input-group-btn']);
            //             $result .= Html::submitButton(Html::beginTag('i',['class'=>'ace-icon fa fa-file-code-o bigger-170']).Html::endTag('i'), ['class' => 'btn btn-xs btn-success']);
            //             $result .= Html::endTag('span');
            //             $result .= Html::endTag('div');
            //             $result .= Html::endForm();
            //             return $result;
            //             //return var_dump($key);
            //             //return Html::a(Html::beginTag('i',['class'=>'ace-icon fa fa-file-code-o bigger-120']).Html::endTag('i'),$url,['class'=>'btn btn-xs btn-info','target'=>'_blank']);
            //         },],
            //     'contentOptions' => ['style'=>'max-width: 140px;'],
            // ],

        ],
    ]); ?>

</div>
