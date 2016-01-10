<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\listings\Listing */

$this->title = Yii::t('app/listing', 'Create Listing');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/listing', 'Listings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="listing-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
