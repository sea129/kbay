<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\category\Category */

$this->title = Yii::t('app/category', 'Create Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/category', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
