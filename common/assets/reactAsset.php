<?php
/**
 */

namespace  common\assets;

use yii\web\AssetBundle;


class ReactAsset extends AssetBundle
{
    public $sourcePath = '@bower/react';
    //public $basePath = '@webroot';
    //public $baseUrl = '@web';

    public $js = [
    	'react.min.js',
    	'react-dom.min.js',
    ];


}


?>
