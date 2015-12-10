<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\products\Product */

$this->title = Yii::t('app/product', 'Update {modelClass}: ', [
    'modelClass' => 'Product',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/product', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app/product', 'Update');
?>
<div class="product-update">

    <h1><?= Html::encode($this->title) ?></h1>
	<h2><?php echo Html::encode($model->sku); ?></h2>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
