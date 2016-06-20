<?php


namespace frontend\assets;

use yii\web\AssetBundle;


class DispatchAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/order-fetch.css',
    ];
    public $js = [
        'js/dispatch/dispatch.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'common\assets\ReactAsset',
    ];
}
