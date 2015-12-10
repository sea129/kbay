<?php
/**
 */

namespace  common\assets;

use yii\web\AssetBundle;


class FontawesomeAsset extends AssetBundle
{
    public $sourcePath = '@bower/font-awesome';
    //public $basePath = '@webroot';
    //public $baseUrl = '@web';
    
    public $css = [
        'css/font-awesome.min.css',
    ];


}


?>