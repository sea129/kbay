<?php

namespace backend\themes\ace;

use yii\web\AssetBundle;

class AceAsset extends AssetBundle {

    public $sourcePath = '@app/themes/ace/assets';
    
    public $css = [
        'css/ace.css',
        'css/font-awesome.css',
        'css/ace-fonts.css',
    ];
    public $js = [
        'js/ace.js',
        'js/ace-elements.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset'
    ];

}
