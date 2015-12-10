<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\packagingpost\PackagingPost */

$this->title = Yii::t('app/packagingpost', 'Create Packaging Post');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/packagingpost', 'Packaging Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="packaging-post-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
