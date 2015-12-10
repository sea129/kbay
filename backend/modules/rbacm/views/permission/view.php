<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use app\modules\rbacm\Module;
/**
 * @var yii\web\View $this
 * @var mdm\admin\models\AuthItem $model
 */
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Module::t('module', 'Permissions'), 'url' => ['index']];
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

