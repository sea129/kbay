<?php

use frontend\themes\ace\AceAsset;
use yii\helpers\Html;
?>
<a data-toggle="dropdown" href="#" class="dropdown-toggle">
    <img class="nav-user-photo" src="<?= $this->assetBundles[AceAsset::className()]->baseUrl ?>/avatars/user.jpg" alt="Jason's Photo" />
    <span class="user-info">
        <small>Welcome,</small>
        Bai Xinxing
    </span>

    <i class="ace-icon fa fa-caret-down"></i>
</a>

<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
    <li>
        <a href="#">
            <i class="ace-icon fa fa-cog"></i>
            Settings
        </a>
    </li>

    <li>
        <a href="profile.html">
            <i class="ace-icon fa fa-user"></i>
            Profile
        </a>
    </li>

    <li class="divider"></li>

    <li>
    <?php 
        if (Yii::$app->user->isGuest) {
            echo Html::a('Login', ['/site/login']);
           
        } else {
            echo Html::a('Logout', ['/site/logout'], ['data-method'=>'post']);
        }
     ?>
        
        </a>
    </li>
</ul>