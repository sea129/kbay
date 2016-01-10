<?php


namespace frontend\assets;

use yii\web\AssetBundle;


class OrderFetchAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/order-fetch.css',
    ];
    public $js = [
        'js/order-fetch.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        //'yii\jui\JuiAsset',
        //'common\assets\ZeroclipboardAsset',
    ];
}
