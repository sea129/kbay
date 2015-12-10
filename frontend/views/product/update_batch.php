<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\products\Product */

$this->title = Yii::t('app/product', 'Update A Batch Product');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/product', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_batch', [
        'model' => $model,
        'mainID' =>$mainID,
    ]) ?>

</div>
