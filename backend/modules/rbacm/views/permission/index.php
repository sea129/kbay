<?php

use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Url;
use yii\helpers\Html;

use app\modules\rbacm\Module;

$this->title = 'Permissions';
$this->params['breadcrumbs'][] = $this->title;

?>
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Module::t('module', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $filterModel,

        'columns'      => [
            [
                'attribute' => 'name',
            ],
            [
                'attribute' => 'description',
            ],
            [
                'attribute' => 'rule_name',
            ],
            [
                'class'      => ActionColumn::className(),
                'template'   => '{update} {delete} {view}',
                'urlCreator' => function ($action, $model) {
                    return Url::to(['/rbacm/permission/' . $action, 'name' => $model['name']]);
                },
                'buttons' =>[
                    'update' => function($url, $model, $key){
                        return Html::a(Html::beginTag('i',['class'=>'ace-icon fa fa-pencil bigger-120']).Html::endTag('i'),$url,['class'=>'btn btn-xs btn-info']);
                    },
                    'delete' => function($url, $model, $key){
                        return Html::a(Html::beginTag('i',['class'=>'ace-icon fa fa-trash-o bigger-120']).Html::endTag('i'),$url,['class'=>'btn btn-xs btn-danger','data-confirm'=>"R U sure you want to delete this item?"]);
                    },
                    'view' => function($url, $model, $key){
                        return Html::a(Html::beginTag('i',['class'=>'ace-icon fa fa-eye bigger-120']).Html::endTag('i'),$url,['class'=>'btn btn-xs btn-success']);
                    },
                ],
                'options' => [
                    'style' => ''
                ],
            ]

        ],

    ])




?>