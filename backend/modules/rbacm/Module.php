<?php

namespace app\modules\rbacm;

use Yii;
use yii\filters\AccessControl;
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\rbacm\controllers';

    /*硬写入可访问的用户名*/
    public $admins = ['admin'];

    public function init()
    {
        parent::init();
        $this->registerTranslations();
        // custom initialization code goes here
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return in_array(Yii::$app->user->identity->username, $this->admins);
                        },
                    ]
                ],
            ],
        ];
    }
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/rbacm/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/modules/rbacm/messages',
            'fileMap' => [
                'modules/rbacm/module' => 'module.php',
            ],
        ];
    }
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/rbacm/' . $category, $message, $params, $language);
    }

}
