<?php
/**
 */

namespace  common\widgets\summernote;

use yii\web\AssetBundle;


class SummernoteAsset extends AssetBundle
{
    public $sourcePath = '@common/widgets/summernote/assets';
    //public $basePath = '@webroot';
    //public $baseUrl = '@web';

    public $css = [
        'css/summernote.css',
        'css/my.css'
    ];
    public $js = [
    	'js/summernote.min.js',
      'js/my.js',
    ];
    //public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'common\assets\FontawesomeAsset',
        'backend\themes\ace\AceAsset',
    ];
}


?>
