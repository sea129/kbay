<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\products\ProductRelation */

$this->title = Yii::t('app/product', 'Create Product Relation');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/product', 'Product Relations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-relation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
