<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'language' => 'zh-CN',
    'basePath' => dirname(__DIR__),
    //'privateImagesPath' => dirname(__DIR__).'/private-images/',
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'gridview' => [
            'class' => '\kartik\grid\Module',
        ],
    ],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'view' => [
            'theme'=> [
                'basePath' => '@frontend/themes/ace',
                'baseUrl' => '@web/themes/ace',
                'pathMap' => [
                    '@frontend/views' => [
                        '@frontend/themes/ace/views',
                    ],
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en-US',
                    /*'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                        'app/product' => 'product.php',
                        'app/ebayaccount' => 'ebayaccount.php',
                        'app/category' => 'category.php',
                    ],*/
                ],
            ],
        ],
        // 'assetManager' => [
        //     'class' => 'yii\web\AssetManager',
        //     'forceCopy' => true,
        // ],
    ],
    'params' => $params,
];
