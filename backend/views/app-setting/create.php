<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\setting\AppSetting */

$this->title = Yii::t('app/setting', 'Create App Setting');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/setting', 'App Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-setting-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
