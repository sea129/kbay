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
  private function fetchPaidOrderReqInit(){
    $request = new Types\GetOrdersRequestType();
    $request->RequesterCredentials = new Types\CustomSecurityHeaderType();
		$request->RequesterCredentials->eBayAuthToken = $this->token;
    return $request;
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
