<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\listingtemplate\ListingTemplate */

$this->title = Yii::t('app/lstingtemplate', 'Create Listing Template');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/lstingtemplate', 'Listing Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="listing-template-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
