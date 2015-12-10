<?php 
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;

use app\modules\rbacm\Module;

$this->title = Module::t('module','Rules');
$this->params['breadcrumbs'][] = $this->title;


 ?>
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Module::t('module', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
  <?php 

  /*echo '<pre>';
  print_r($dataProvider);
  echo '</pre>';
  exit();*/
   ?>
 <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns'=>[
          ['class'=>'yii\grid\SerialColumn'],
          
            'name',
            [
              'label'=>'Class Name',
              'content'=>function($model, $key, $index, $column){
                return $model->className();
              },
            ],
            'createdAt:date',
            'updatedAt:date',
          [
              'class' => 'yii\grid\ActionColumn',
              'template'   => '{update} {delete}',
              'urlCreator' => function ($action, $model) {
                  return Url::to(['/rbacm/rule/' . $action, 'name' => $model->name]);
              },
              'buttons' =>[
                  'update' => function($url, $model, $key){
                      return Html::a(Html::beginTag('i',['class'=>'ace-icon fa fa-pencil bigger-120']).Html::endTag('i'),$url,['class'=>'btn btn-xs btn-info']);
                  },
                  'delete' => function($url, $model, $key){
                      return Html::a(Html::beginTag('i',['class'=>'ace-icon fa fa-trash-o bigger-120']).Html::endTag('i'),$url,['class'=>'btn btn-xs btn-danger','data-confirm'=>"R U sure you want to delete this item?"]);
                  },
              ],
              'options' => [
                  'style' => ''
              ],
          ]
        ],
        /*'filterModel'  => $searchModel,
        'columns'=>[
          ['class' => 'yii\grid\SerialColumn'],
          [
            'attribute'=>'name',
          ],
          [
            'attribute'=>'className',
          ],


          
        ]*/

      

    ])




?>