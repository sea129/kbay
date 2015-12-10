<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\products\ProductRelationtSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app/product', 'Product Relations');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-relation-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app/product', 'Create Product Relation'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'main',
            'sub',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
