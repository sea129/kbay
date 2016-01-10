<?php


namespace frontend\assets;

use yii\web\AssetBundle;


class ListingSyncAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/listing-sync.css',
    ];
    public $js = [
        'js/listing-sync.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        //'yii\jui\JuiAsset',
        //'common\assets\ZeroclipboardAsset',
    ];
}
