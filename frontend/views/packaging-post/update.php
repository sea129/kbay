<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\packagingpost\PackagingPost */

$this->title = Yii::t('app/packagingpost', 'Update {modelClass}: ', [
    'modelClass' => 'Packaging Post',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/packagingpost', 'Packaging Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app/packagingpost', 'Update');
?>
<div class="packaging-post-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
