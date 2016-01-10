<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ProductAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        /*'css/ebayaccount.css',*/
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $js = [
        'js/canvas-to-blob.js',
        'js/product.js',
    ];
    public $depends = [
      'yii\web\YiiAsset',
      'yii\bootstrap\BootstrapPluginAsset',

    ];
}
