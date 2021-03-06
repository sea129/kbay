<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\products\Product */

$this->title = Yii::t('app/product', 'Create Product');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/product', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
