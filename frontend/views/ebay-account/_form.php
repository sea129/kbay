<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\ebayaccounts\EbayAccount */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ebay-account-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'seller_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'store_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'shipping_info')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'warranty_info')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'payment_info')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'contact_info')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'listing_template_id')->textInput() ?>

    <?= $form->field($model, 'token')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'token_expiration')->textInput() ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'listing_assets_url')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app/ebayaccount', 'Create') : Yii::t('app/ebayaccount', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
