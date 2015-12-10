<?php

use backend\themes\ace\AceAsset;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

AceAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="no-skin">
        <?php $this->beginBody() ?>
        
        <?= $this->render('//layouts/_navbar') ?>
        
        <div class="main-container" id="main-container">
            <?= $this->render('//layouts/_sidebar') ?>
            
            <div class="main-content">
                <?= $this->render('//layouts/_breadcrumbs') ?>
                <div class="page-content">
                    <div class="row">
                      <div class="col-xs-12">
                       <?= $content ?>
                      </div><!-- /.col -->
                    </div><!-- /.row -->
                    

                </div>
            </div>

            <?= $this->render('//layouts/_footer') ?>
            <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
                <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
            </a>
        </div>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
