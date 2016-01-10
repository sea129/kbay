<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\setting\AppSetting */

$this->title = Yii::t('app/setting', 'Update {modelClass}: ', [
    'modelClass' => 'App Setting',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/setting', 'App Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = Yii::t('app/setting', 'Update');
?>
<div class="app-setting-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
