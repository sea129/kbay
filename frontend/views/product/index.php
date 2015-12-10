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
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'sku',
            'name',
            // 'mini_desc:ntext',
            // 'stock_qty',
            // 'cost',
            // 'description:ntext',
            // 'specs:ntext',
            // 'category_id',
            // 'user_id',
            // 'stock_location',
            // 'supplier_id',
            // 'packaging_id',
            // 'weight',
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
                        return Html::a('<span class="ace-icon fa fa-trash-o"></span>',$url,['class'=>'btn btn-xs btn-danger','data-confirm'=>Yii::t('yii', 'Are you sure you want to delete this item?')]);
                    },
                    'add-batch-product' => function($url, $model, $key){
                        if($model->qty_per_order===1){
                            return Html::a('<span class="ace-icon fa fa-plus"></span>',Url::to(['product/add-batch-product','mainID'=>$key]),['class'=>'btn btn-xs btn-warning']);
                        }else{

                        }
                    },
                    'update'=>function($url,$model,$key){
                        if($model->stock_qty===null){
                            return Html::a('<span class="ace-icon fa fa-pencil"></span>',Url::to(['product/update-batch-product','id'=>$key]),['class'=>'btn btn-xs btn-warning']);
                        }else{
                            return Html::a('<span class="ace-icon fa fa-pencil"></span>',$url,['class'=>'btn btn-xs btn-info']);
                        }
                    },
                ]
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=> Yii::t('app/product','Preview'),
                'template'   => '{preview-desc}',
                'buttons' => ['preview-desc' => function($url, $model, $key) use ($ebayAccounts){
                        $result = Html::beginForm($url,'post',['target'=>'_blank']);
                        $result .= Html::beginTag('div',['class'=>'input-group']);
                        $result .= Html::dropDownList('ebayAccID',null,$ebayAccounts,['class'=>'form-control']);
                        $result .= Html::beginTag('span',['class'=>'input-group-btn']);
                        $result .= Html::submitButton(Html::beginTag('i',['class'=>'ace-icon fa fa-file-code-o bigger-170']).Html::endTag('i'), ['class' => 'btn btn-xs btn-success']);
                        $result .= Html::endTag('span');
                        $result .= Html::endTag('div');
                        $result .= Html::endForm();
                        return $result;
                        //return var_dump($key);
                        //return Html::a(Html::beginTag('i',['class'=>'ace-icon fa fa-file-code-o bigger-120']).Html::endTag('i'),$url,['class'=>'btn btn-xs btn-info','target'=>'_blank']);
                    },],
                'contentOptions' => ['style'=>'max-width: 140px;'],
            ],

        ],
    ]); ?>

</div>
