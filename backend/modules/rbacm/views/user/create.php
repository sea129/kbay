<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\user */

use app\modules\rbacm\Module;

$this->title = Module::t('module', 'Create User');
$this->params['breadcrumbs'][] = ['label' => Module::t('module', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
