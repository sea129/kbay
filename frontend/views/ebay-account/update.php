<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\ebayaccounts\EbayAccount */

$this->title = Yii::t('app/ebayaccount', 'Update {modelClass}: ', [
    'modelClass' => 'Ebay Account',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/ebayaccount', 'Ebay Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app/ebayaccount', 'Update');
?>
<div class="ebay-account-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
