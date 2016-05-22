<?php
  namespace frontend\components;

  use Yii;
  use Doctrine\Common\Cache\FilesystemCache;
  use RemoteImageUploader;
  use common\models\setting\AppSetting;
  class JHelper
  {
    public static function uploadHelper($file){
      $imgurKey = AppSetting::findOne('imgur_key')->string_value;
      $imgurSecret = AppSetting::findOne('imgur_secret')->string_value;
      $imgurToken = AppSetting::findOne('imgur_token')->string_value;

      $cacher = new FilesystemCache('/tmp');
      $uploader = RemoteImageUploader\Factory::create('Imgur', array(
        'cacher'         => $cacher,
        'api_key'        => $imgurKey,
        'api_secret'     => $imgurSecret,
        // if you have `refresh_token` you can set it here
        // to pass authorize action.
        'refresh_token' => $imgurToken,
        // If you don't want to authorize by yourself, you can set
        // this option to `true`, it will requires `username` and `password`.
        // But sometimes Imgur requires captcha for authorize so this option
        // will be failed. And you need to set it to `false` and do it by
        // yourself.
        'auto_authorize' => false,
        'username'       => '',
        'password'       => ''
      ));

      $uploader->authorize();
      $url = $uploader->upload($file);
      return $url;
    }
  }
 ?>
