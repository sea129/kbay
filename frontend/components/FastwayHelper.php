<?php
  namespace frontend\components;

  use Yii;
  class FastwayHelper
  {
    public static function isAvailable($postcode){
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
