<?php
namespace frontend\components\ebayapi;

use Yii;
use DTS\eBaySDK\Constants;
use DTS\eBaySDK\Shopping\Services;
use DTS\eBaySDK\Shopping\Types;
use DTS\eBaySDK\Shopping\Enums;
use yii\web\NotFoundHttpException;
/**
 *shopping api
 */
class ShoppingApi
{
  const APP_ID = 'jhcfa367d-11b7-4d4e-8293-0fb8e006303';
  const API_VERSION = 955;

  protected $service;

  function __construct()
  {
    $this->service = new Services\ShoppingService(array(
        'apiVersion' => self::API_VERSION,
        'appId' => self::APP_ID
    ));
  }

  public function getItem($itemID){
    $request = new Types\GetSingleItemRequestType();
    $request->ItemID = $itemID;
    $request->IncludeSelector = 'Details';
    $response = $this->service->getSingleItem($request);
    if ($response->Ack !== 'Failure') {
      $item = $response->Item;
      //return var_dump($item);
      return ['title'=>$item->Title,'price'=>$item->CurrentPrice->value,'categoryID'=>$item->PrimaryCategoryID];
    }else{
      return "Failure";
    }
  }
}

 ?>
