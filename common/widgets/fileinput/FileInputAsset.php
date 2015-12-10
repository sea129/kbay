<?php  
namespace common\widgets\fileinput;

use yii\web\AssetBundle;


class FileInputAsset extends AssetBundle
{
    public $sourcePath = '@common/widgets/fileinput/assets';
    //public $basePath = '@webroot';
    //public $baseUrl = '@web';
    
    public $css = [
    ];
    public $js = [
    	'js/canvas-to-blob.min.js',
    ];
    //public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'kartik\file\FileInputAsset',
    ];
}

?>