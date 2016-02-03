<?php

namespace frontend\components\ebayapi;

use DTS\eBaySDK\Constants;
use DTS\eBaySDK\Trading\Services;
use DTS\eBaySDK\Trading\Types;
use DTS\eBaySDK\Trading\Enums;

use frontend\models\productebaylisting\ProductEbayListing;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
* 继承ebayAPI class, 关于操作在ebay上listing的类
*/
class EbayListing extends EbayApi
{

	/**
	 * common code of init get active listing request.
	 */
	private function activeListReqInit()
	{
		$request = new Types\GetMyeBaySellingRequestType();
		$request->RequesterCredentials = new Types\CustomSecurityHeaderType();
		$request->RequesterCredentials->eBayAuthToken = $this->token;
		$request->ActiveList = new Types\ItemListCustomizationType();
		$request->ActiveList->Include = true;

		return $request;
	}

	private function getItemReqInit(){
		$request = new Types\GetitemRequestType();
		$request->RequesterCredentials = new Types\CustomSecurityHeaderType();
		$request->RequesterCredentials->eBayAuthToken = $this->token;

		return $request;
	}

	public function getItemPicUrl($itemID){
		$service = $this->tradingServiceInit();
		$itemRequest = $this->getItemReqInit();
		$itemRequest->ItemID = $itemID;
		$reqItem = $service->getItem($itemRequest);
		if ($reqItem->Ack !== 'Failure' && isset($reqItem->Item)) {
			return $reqItem->Item->PictureDetails->GalleryURL;
		}else{
			return "error";
		}
	}
	/**
	 * return listing info on that page based on pagenumber just one ebay api virtual page
	 */
	public function getListingPage($pageNumber)
	{
		$appSetting = \common\models\setting\AppSetting::findOne('listing_sync_entries_per_page');
		$request = $this->activeListReqInit();
		$request->ActiveList->Pagination = new Types\PaginationType();
		$request->ActiveList->Pagination->EntriesPerPage = $appSetting->number_value;
		$request->ActiveList->Pagination->PageNumber =$pageNumber;
		$service = $this->tradingServiceInit();
		$response=$service->getMyeBaySelling($request);
		$result=$this->getResponseError($response);
		if ($response->Ack !== 'Failure' && isset($response->ActiveList)) {
			foreach ($response->ActiveList->ItemArray->Item as $item) {
				$listing = [
						'item_id'=>$item->ItemID,
						'price'=>$item->BuyItNowPrice->value,
						'title'=>$item->Title,
						'qty'=>$item->QuantityAvailable,
						'sold_qty'=>$item->Quantity-$item->QuantityAvailable,
					];

				if(isset($item->SKU)){
					$listing['sku'] =$item->SKU;
				}
				// $itemRequest = $this->getItemReqInit();
				// $itemRequest->ItemID = $item->ItemID;
				// $reqItem = $service->getItem($itemRequest);
				// if ($reqItem->Ack !== 'Failure' && isset($reqItem->Item)) {
				// 	$listing['sold_qty']= $reqItem->Item->SellingStatus->QuantitySold;
				// }else{
				// 	$result['Error'][]=\Yii::t('app/listing', 'error on quantity sold', []);
				// }
				$result['listings'][]=$listing;
			}
		}else{
			$result['Error'][]=\Yii::t('app/listing', 'error on my ebay selling', []);
		}

		return $result;
	}

	/**
	 * get how many listings to be sync.so that I can get how many pages
	 */
	public function getPreListingInfo(){
		$request = $this->activeListReqInit();
		$appSetting = \common\models\setting\AppSetting::findOne('listing_sync_entries_per_page');
		$request->ActiveList->Pagination = new Types\PaginationType();
		$request->ActiveList->Pagination->EntriesPerPage = $appSetting->number_value;
		$service = $this->tradingServiceInit();
		$response=$service->getMyeBaySelling($request);
		$result=$this->getResponseError($response);
		if ($response->Ack !== 'Failure' && isset($response->ActiveList)) {
			$result['data']=$response->ActiveList->PaginationResult->TotalNumberOfEntries;
			$result['totalPages']=$response->ActiveList->PaginationResult->TotalNumberOfPages;
		}else{
			$result['Error'][]=\Yii::t('app/listing', 'connect Failure', []);
		}

		return $result;
	}
	/**
	 * 拿到一个产品在所有此user ebay上的信息
	 * @param  [type] $sku [description]
	 * @return [type]      [description]
	 */
	// public function getListingInAllEbay($sku)
	// {
	// 	$service = new Services\TradingService(array(
	// 	    'apiVersion' => Parent :: COMPATABILITY_LEVEL,
	// 	    'siteId' => Parent :: SITE_ID,
	// 	));
	// 	$response = [];
	//
	// 	$request = new Types\GetMyeBaySellingRequestType();
	//
	// 	$request->RequesterCredentials = new Types\CustomSecurityHeaderType();
	// 	foreach ($this->tokens as $seller_id => $token) {
	// 		if($token==null){
	//
	// 		}else{
	// 			$request->RequesterCredentials->eBayAuthToken = $token;
	// 			$request->ActiveList = new Types\ItemListCustomizationType();
	// 			$request->ActiveList->Include = true;
	// 			$request->ActiveList->Pagination = new Types\PaginationType();
	// 			$request->ActiveList->Pagination->EntriesPerPage = 1;
	// 			$request->ActiveList->Sort = Enums\ItemSortTypeCodeType::C_CURRENT_PRICE_DESCENDING;
	// 			$request->ActiveList->Pagination->PageNumber = 1;
	//
	// 			$response[]=$service->getMyeBaySelling($request);
	// 		}
	// 	}
	// 	return $response;
	// }

	/**
	 * 拿到提供ebay账号下的所有listings信息,主要拿到所有itemID
	 * @return [type] [description]
	 */
	// public function syncListings($sync = false)
	// {
	// 	$service = new Services\TradingService(array(
	// 	    'apiVersion' => Parent :: COMPATABILITY_LEVEL,
	// 	    'siteId' => Parent :: SITE_ID,
	// 	));
	//
	// 	//$result = [];
	//
	// 	$request = new Types\GetMyeBaySellingRequestType();
	// 	$request->RequesterCredentials = new Types\CustomSecurityHeaderType();
	// 	if(is_array($this->tokens)){//所有ebay的所有listing，由于数据量可能过于庞大，暂时不做。
	// 		return false;
	// 	}else{
	//
	// 		$request->RequesterCredentials->eBayAuthToken = $this->tokens;
	// 		$request->ActiveList = new Types\ItemListCustomizationType();
	// 		$request->ActiveList->Include = true;
	// 		$request->ActiveList->Pagination = new Types\PaginationType();
	// 		$request->ActiveList->Pagination->EntriesPerPage = 100;
	// 		$pageNum  = 1;
	//
	// 		$synListingInfo = ProductEbayListing::find()->indexBy('sku')->allOfEbay($this->ebayID,$db = null);
	// 		$result = [
	// 			'updated' =>[],
	// 			'added' => [],
	// 			'deleted' =>[],
	// 		];
	// 		do{
	// 			$request->ActiveList->Pagination->PageNumber = $pageNum;
	//
	// 			$response=$service->getMyeBaySelling($request);
	//
	// 			if (isset($response->Errors)) {
	// 	        foreach ($response->Errors as $error) {
	// 	        	$result[($error->SeverityCode === Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning')][]=['ShortMessage'=>$error->ShortMessage,'LongMessage'=>$error->LongMessage,];
	// 	        }
	//
	// 	    }
	//
	// 		    if ($response->Ack !== 'Failure' && isset($response->ActiveList)) {
	//
	// 		        foreach ($response->ActiveList->ItemArray->Item as $item) {
	//
	// 		            //情况一，下载的listing已经在表里，但有些数据错误或过时，这里及时更新同步。
	// 		            if(ArrayHelper::keyExists($item->SKU,$synListingInfo)){
	// 		            	if($item->QuantityAvailable!=$synListingInfo[$item->SKU]->qty
	// 		            		|| $item->BuyItNowPrice->value!=$synListingInfo[$item->SKU]->price
	// 		            		|| $item->Title!=$synListingInfo[$item->SKU]->title
	// 		            		|| $item->ItemID!=$synListingInfo[$item->SKU]->item_id)
	// 		            	{
	// 		            		$synListingInfo[$item->SKU]->price = $item->BuyItNowPrice->value;
	// 			            	$synListingInfo[$item->SKU]->item_id = $item->ItemID;
	// 			            	$synListingInfo[$item->SKU]->title = $item->Title;
	// 			            	$synListingInfo[$item->SKU]->qty = $item->QuantityAvailable;
	// 			            	if(!$synListingInfo[$item->SKU]->save()){
	// 			            		throw new NotFoundHttpException('Fail to save product listing ebay info: '.$item->SKU);
	// 			            	}else{
	// 			            		$result['updated'][]=$item->SKU;
	// 			            	}
	// 		            	}
	// 		            	unset($synListingInfo[$item->SKU]);
	//
	//
	// 		            }else{//表里没有下载的listing，这里要把listing数据存入表里。
	// 		            	$newSync = new ProductEbayListing();
	// 		            	$newSync->sku = $item->SKU;
	// 		            	$newSync->ebay_account_id=$this->ebayID;
	// 		            	$newSync->item_id=$item->ItemID;
	// 		            	$newSync->title = $item->Title;
	// 		            	$newSync->price = $item->BuyItNowPrice->value;
	// 		            	$newSync->qty = $item->QuantityAvailable;
	// 		            	if(!$newSync->save()){
	// 		            		throw new NotFoundHttpException('Fail to insert new product listing ebay info: '.$item->SKU);
	// 		            	}else{
	// 		            		$result['added'][]=$item->SKU;
	// 		            	}
	// 		            }
	//
	// 		        }
	//
	// 		    }
	//
	// 			$pageNum += 1;
	// 		}while(isset($response->ActiveList) && $pageNum <= $response->ActiveList->PaginationResult->TotalNumberOfPages);
	//
	// 		//删除ebay上已经没有的listing
	// 		foreach ($synListingInfo as $sku => $obj) {
	// 			if(!$obj->delete()){
	// 				throw new NotFoundHttpException('Fail to delete: '.$sku);
	// 			}else{
	// 				$result['deleted'][]=$sku;
	// 			}
	// 		}
	//
	//
	// 	}//end else
	//
	// 	return $result;
	// 	//return "1";
	// }





}


?>
