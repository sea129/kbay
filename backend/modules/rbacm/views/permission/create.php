<?php


$this->title = 'Create new permission';
$this->params['breadcrumbs'][] = $this->title;

?>



<?= $this->render('_form', [
    'model' => $model,
]) ?>
