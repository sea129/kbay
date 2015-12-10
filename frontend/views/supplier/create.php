<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\supplier\Supplier */

$this->title = Yii::t('app/supplier', 'Create Supplier');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/supplier', 'Suppliers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
