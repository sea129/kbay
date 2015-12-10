<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ListView;
use yii\grid\GridView;

use app\modules\rbacm\Module;
/**
 * @var yii\web\View $this
 * @var mdm\admin\models\AuthItem $model
 */
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Module::t('module', 'Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Module::t('module', 'Update'), ['update', 'name' => $model->name], ['class' => 'btn btn-primary']) ?>
        <?php
        echo Html::a(Module::t('module', 'Delete'), ['delete', 'name' => $model->name], [
            'class' => 'btn btn-danger',
            'data-confirm' => Module::t('module', 'Are you sure to delete this item?'),
            'data-method' => 'post',
        ]);
        ?>
    </p>
    <?php
    echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'description:ntext',
            'rule_name',
            'data:ntext',
        ],
    ]);
    ?>

</div>
<div class="row">
    <div class="col-xs-12">
        <h3 class="header smaller lighter blue">Assigned Permissions</h3>
        <?php 
            echo GridView::widget([
                'dataProvider' => $allPermissions,
                'columns'      => [
                    /*[
                        'attribute' => 'name',
                    ],
                    [
                        'attribute' => 'description',
                    ],
                    [
                        'attribute' => 'rule_name',
                    ],*/
                    ['class' => 'yii\grid\SerialColumn'],
                    'name',
                    'description:ntext',
                    'ruleName',
                    'data:ntext',
                ],
            ]);
         ?>
    </div>
    
</div>
<div class="row">
    <div class="col-xs-12">
        <h3 class="header smaller lighter blue">Update Children</h3>
            <?= $this->render('_children_form', [
                'model' => $model,
                'avaliable' => $avaliable,
                'assigned' => $assigned,
            ]) ?>
    </div>
</div>

