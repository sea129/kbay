<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\listingtemplate\ListingTemplate */

$this->title = Yii::t('app/lstingtemplate', 'Update {modelClass}: ', [
    'modelClass' => 'Listing Template',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/lstingtemplate', 'Listing Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app/lstingtemplate', 'Update');
?>
<div class="listing-template-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
