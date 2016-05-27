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

    public static function isFastwayAvailable($postcode){
      if( ($postcode>=2000&&$postcode<=2234) ||
          ($postcode>=2555&&$postcode<=2560) ||
          ($postcode>=2564&&$postcode<=2567) ||
          ($postcode==2570) ||
          ($postcode==2745) ||
          ($postcode>=2747&&$postcode<=2753) ||
          ($postcode==2756) ||
          ($postcode>=2759&&$postcode<=2763) ||
          ($postcode>=2765&&$postcode<=2768) ||
          ($postcode==2770)
       ){
        return true;
      }else{
        return false;
      }
    }
  }
 ?>
