<?php

use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;

use app\modules\rbacm\Module;

$this->title = Module::t('module','Roles');
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
            
            'name',
            'description',
            'rule_name',
            [
                'class' => 'yii\grid\ActionColumn',
                'template'   => '{update} {delete} {view}',
                'urlCreator' => function ($action, $model) {
                    return Url::to(['/rbacm/role/' . $action, 'name' => $model['name']]);
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