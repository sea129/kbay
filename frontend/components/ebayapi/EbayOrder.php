<?php
namespace frontend\components\ebayapi;
use DTS\eBaySDK\Constants;
use DTS\eBaySDK\Trading\Services;
use DTS\eBaySDK\Trading\Types;
use DTS\eBaySDK\Trading\Enums;

use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class EbayOrder extends EbayApi
{
  private function getOrderReqInit(){
    $request = new Types\GetOrdersRequestType();
    $request->RequesterCredentials = new Types\CustomSecurityHeaderType();
		$request->RequesterCredentials->eBayAuthToken = $this->token;
    return $request;
  }

  private function commonGetOrdersRequest($createFrom,$createTo){
    $appSetting = \common\models\setting\AppSetting::findOne('fetch_order_entries_per_page');
    $request = $this->getOrderReqInit();
    $request->Pagination = new Types\PaginationType();
    $request->Pagination->EntriesPerPage = $appSetting->number_value;
    $request->CreateTimeFrom = $createFrom;
    $request->CreateTimeTo =  $createTo;
    $request->OrderStatus = 'Completed';
    $request->OrderRole = 'Seller';
    $request->IncludeFinalValueFee = true;
    return $request;
  }

  public function getOrdersByTime($createFrom, $createTo, $pageNum){
    $request = $this->commonGetOrdersRequest($createFrom,$createTo);
    $request->Pagination->PageNumber = (int)$pageNum;
    $service = $this->tradingServiceInit();
    $response = $service->getOrders($request);
    if($response->Ack !== 'Failure'){
      $result = $this->getResponseError($response);
      if(isset($result['Error'])){
        return $result;
      }else{
        $result['orders'] = $response->OrderArray->Order;
        $result['moreOrders'] = $response->HasMoreOrders;
        $result['orderCounts'] = $response->PaginationResult->TotalNumberOfEntries;
        return $result;
      }
    }else{
      return ['Error'=>['Api Call ack Failure']];
    }
  }

  public function getOrdersByID($orderIDArr,$pageNum){
    $request = $this->getOrderReqInit();
    $appSetting = \common\models\setting\AppSetting::findOne('fetch_order_entries_per_page');
    $request->Pagination = new Types\PaginationType();
    $request->Pagination->EntriesPerPage = $appSetting->number_value;
    $request->IncludeFinalValueFee = true;
    $request->Pagination->PageNumber = (int)$pageNum;
    //return $request->OrderIDArray;

    $request->OrderIDArray = new Types\OrderIDArrayType();
    $request->OrderIDArray->OrderID = $orderIDArr;
    $service = $this->tradingServiceInit();
    $response = $service->getOrders($request);
    if($response->Ack !== 'Failure'){
      $result = $this->getResponseError($response);
      if(isset($result['Error'])){
        return $result;
      }else{
        $result['orders'] = $response->OrderArray->Order;
        $result['moreOrders'] = $response->HasMoreOrders;
        $result['orderCounts'] = $response->PaginationResult->TotalNumberOfEntries;
        return $result;
      }
    }else{
      $result = $this->getResponseError($response);
      return $result;
    }
  }

  public function ebayOfficialTime(){
    return $this->getOfficialTime();
  }
  /**
	 * get how many Orders to be fetch.
   * $createFrom is the createTo of last fetch, if createTo is null, default will be 24 hrs before now
	 */
	public function getPreFetchInfo($createFrom,$createTo){
    $appSetting = \common\models\setting\AppSetting::findOne('fetch_order_entries_per_page');
    $request = $this->fetchPaidOrderReqInit();
    $request->Pagination = new Types\PaginationType();
    $request->Pagination->EntriesPerPage = $appSetting->number_value;
    $request->CreateTimeFrom = $createFrom;
    $request->CreateTimeTo =  $createTo;
    $request->OrderStatus = 'Completed';
    $request->OrderRole = 'Seller';
    $service=$this->tradingServiceInit();
    $response=$service->getOrders($request);
    $result=$this->getResponseError($response);
    if ($response->Ack !== 'Failure' && isset($response->PaginationResult->TotalNumberOfEntries)) {
			$result['preOrderData']=$response->PaginationResult->TotalNumberOfEntries;
			$result['totalPages']=$response->PaginationResult->TotalNumberOfPages;
		}else{
			$result['Error'][]=\Yii::t('app/listing', 'connect Failure', []);
		}
    return $result;
	}

  public function mainFetch($createFrom,$createTo,$pageNumber){
    $request = $this->commonFetchRequest($createFrom,$createTo);
    $request->Pagination->PageNumber = (int)$pageNumber;
    $service=$this->tradingServiceInit();
    $response=$service->getOrders($request);
    $result=$this->getResponseError($response);
    if($response->Ack!=='Failure' && isset($response->OrderArray)){
      $result['orders']=$response->OrderArray->Order;
      $result['hasMoreOrders']=$response->HasMoreOrders;
    }else{
      $result['Error'][]=\Yii::t('app/listing', 'connect Failure', []);
    }
    return $result;
  }

  private function commonFetchRequest($createFrom,$createTo){
    $appSetting = \common\models\setting\AppSetting::findOne('fetch_order_entries_per_page');
    $request = $this->fetchPaidOrderReqInit();
    $request->Pagination = new Types\PaginationType();
    $request->Pagination->EntriesPerPage = $appSetting->number_value;
    $request->CreateTimeFrom = $createFrom;
    $request->CreateTimeTo =  $createTo;
    $request->OrderStatus = 'Completed';
    $request->OrderRole = 'Seller';
    $request->IncludeFinalValueFee = true;


    return $request;
  }



}
?>
