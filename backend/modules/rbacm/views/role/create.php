<?php


$this->title = 'Create new role';
$this->params['breadcrumbs'][] = $this->title;

?>



<?= $this->render('_form', [
    'model' => $model,
]) ?>
