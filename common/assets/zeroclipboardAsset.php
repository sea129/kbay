<?php
/**
 */

namespace  common\assets;

use yii\web\AssetBundle;


class ZeroclipboardAsset extends AssetBundle
{
    public $sourcePath = '@bower/zeroclipboard/dist';
    //public $basePath = '@webroot';
    //public $baseUrl = '@web';
    
    /*public $css = [
        'css/font-awesome.min.css',
    ];*/
    public $js = [
    	'ZeroClipboard.min.js',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        //'common\assets\ZeroclipboardAsset',
    ];


}


?>