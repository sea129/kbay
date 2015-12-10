<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\ebayaccounts\EbayAccountSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ebay-account-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'seller_id') ?>

    <?= $form->field($model, 'store_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'shipping_info') ?>

    <?php // echo $form->field($model, 'warranty_info') ?>

    <?php // echo $form->field($model, 'payment_info') ?>

    <?php // echo $form->field($model, 'contact_info') ?>

    <?php // echo $form->field($model, 'listing_template_id') ?>

    <?php // echo $form->field($model, 'token') ?>

    <?php // echo $form->field($model, 'token_expiration') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'listing_assets_url') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app/ebayaccount', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app/ebayaccount', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
