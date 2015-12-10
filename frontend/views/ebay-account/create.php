<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\ebayaccounts\EbayAccount */

$this->title = Yii::t('app/ebayaccount', 'Create Ebay Account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/ebayaccount', 'Ebay Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ebay-account-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
