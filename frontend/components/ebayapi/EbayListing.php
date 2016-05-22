<?php

namespace frontend\components\ebayapi;

use DTS\eBaySDK\Constants;
use DTS\eBaySDK\Trading\Services;
use DTS\eBaySDK\Trading\Types;
use DTS\eBaySDK\Trading\Enums;

//use frontend\models\productebaylisting\ProductEbayListing;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
* 继承ebayAPI class, 关于操作在ebay上listing的类
*/
class EbayListing extends EbayApi
{

	// public function verifyAddFixedPriceListing($listingItem)
	// {
	// 		$item = $this->buildItem($listingItem);
	//
	// 		$request = new Types\VerifyAddFixedPriceItemRequestType();
	// 		$request->RequesterCredentials = new Types\CustomSecurityHeaderType();
	// 		$request->RequesterCredentials->eBayAuthToken = $this->token;
	// 		$request->Item = $item;
	// 		$service = new Services\TradingService(array(
	// 	      'apiVersion' => EbayApi::COMPATABILITY_LEVEL,
	// 	      'siteId' => EbayApi::SITE_ID,
	// 	      'authToken' => $this->token,
	// 	      'devId' => EbayApi::DEV_ID,
	// 	      'appId' => EbayApi::APP_ID,
	// 	      'certId' => EbayApi::CERT_ID,
	// 	  ));
	// 		$response = $service->verifyAddFixedPriceItem($request);
	// 		$result = [];
	// 		if (isset($response->Errors)) {
	// 		    foreach ($response->Errors as $error) {
	// 					$result['error'][]=$error->ShortMessage;
	// 		      $result['error'][]=$error->LongMessage;
	// 		    }
	// 		}
	//
	// 		if($response->Ack !== 'Failure'){
	// 			$result['fee'] = var_dump($response);
	// 			// foreach ($response->Fees->Fee as $key => $value) {
	// 			// 	if($value->Name=='ListingFee'){
	// 			// 		$result['fee'] = $value->Fee->value;
	// 			// 	}
	// 			// 	if(isset($value->PromotionalDiscount)){
	// 			// 		$result['discount'] = $value->Fee->value;
	// 			// 	}
	// 			// }
	// 			//$result['fee'] = var_dump($response->Fees->Fee[14]->Fee);
	// 		}
	// 		//$result['verified'] = $response->Ack !== 'Failure';
	// 		//return $response->Ack !== 'Failure';
	// 		//$result['item'] = $item;
	// 		return $result;
	// }

	private function buildItem($listingItem)
	{
		$item = new Types\ItemType();
		// bad coding hard coded some constants
		$item->Country = 'AU';
		$item->Currency = 'AUD';
		$item->ListingDuration = Enums\ListingDurationCodeType::C_GTC;
		$item->ListingType = Enums\ListingTypeCodeType::C_FIXED_PRICE_ITEM;
		$item->ConditionID = 1000;
		$item->DispatchTimeMax = 1;
		$item->ReturnPolicy = new Types\ReturnPolicyType();
		$item->ReturnPolicy->ReturnsAcceptedOption = 'ReturnsAccepted';
		$item->ReturnPolicy->RefundOption = 'MoneyBack';
		$item->ReturnPolicy->ReturnsWithinOption = 'Days_14';
		$item->ReturnPolicy->ShippingCostPaidByOption = 'Buyer';
		$item->ShippingDetails = new Types\ShippingDetailsType();
		$item->ShippingDetails->ShippingType = Enums\ShippingTypeCodeType::C_FLAT;

		$item->Quantity = (int)$listingItem->qty;
		$item->StartPrice = new Types\AmountType(array('value' => (double)$listingItem->price));
		$item->BestOfferDetails = new Types\BestOfferDetailsType();
		//return var_dump($listingItem->bestOffer);
		if($listingItem->bestOffer!="false"){
			$item->BestOfferDetails->BestOfferEnabled = true;
			$item->ListingDetails = new Types\ListingDetailsType();
			$item->ListingDetails->BestOfferAutoAcceptPrice = new Types\AmountType(array('value' => (double)$listingItem->bestOffer[0]));
			$item->ListingDetails->MinimumBestOfferPrice = new Types\AmountType(array('value' => (double)$listingItem->bestOffer[1]));
		}else{
			$item->BestOfferDetails->BestOfferEnabled = false;
		}

		$item->PrimaryCategory = new Types\CategoryType();
		$item->PrimaryCategory->CategoryID = $listingItem->primaryCate;
		$item->Title = $listingItem->title;
		$item->Description = "<![CDATA[" . $listingItem->description . "]]>";
		//$item->Description = "test";
		$item->SKU = $listingItem->sku;
		$item->Location = $listingItem->location;
		$item->PictureDetails = new Types\PictureDetailsType();
		$item->PictureDetails->GalleryType = Enums\GalleryTypeCodeType::C_GALLERY;
		$item->PictureDetails->PictureURL = $listingItem->picture;
		$item->PaymentMethods = array(
		    'PayPal'
		);
		$item->PayPalEmailAddress = $listingItem->paypal;
		$item->AutoPay = true;
		$shippingService = new Types\ShippingServiceOptionsType();
		$shippingService->ShippingServicePriority = 1;
		$shippingService->ShippingService = $listingItem->shippingService;
		if($listingItem->shippingCost=="true"){
			$shippingService->FreeShipping = true;
			$shippingService->ShippingServiceCost = new Types\AmountType(array('value' => (double)0));
			$shippingService->ShippingServiceAdditionalCost = new Types\AmountType(array('value' => (double)0));
		}else{
			$shippingService->ShippingServiceCost = new Types\AmountType(array('value' => (double)$listingItem->shippingCost[0]));
			$shippingService->ShippingServiceAdditionalCost = new Types\AmountType(array('value' => (double)$listingItem->shippingCost[1]));
		}

		$item->ShippingDetails->ShippingServiceOptions[] = $shippingService;
		return $item;
	}

	public function addFixedPriceListing($listingItem)
	{
		$request = new Types\AddFixedPriceItemRequestType();
		$request->RequesterCredentials = new Types\CustomSecurityHeaderType();
		$request->RequesterCredentials->eBayAuthToken = $this->token;
		//$item = new Types\ItemType();
		// bad coding
		// $item->Country = 'AU';
		// $item->Currency = 'AUD';
		// $item->ListingDuration = Enums\ListingDurationCodeType::C_GTC;
		// $item->ListingType = Enums\ListingTypeCodeType::C_FIXED_PRICE_ITEM;
		// $item->ConditionID = 1000;
		// $item->DispatchTimeMax = 1;
		// $item->ReturnPolicy = new Types\ReturnPolicyType();
		// $item->ReturnPolicy->ReturnsAcceptedOption = 'ReturnsAccepted';
		// $item->ReturnPolicy->RefundOption = 'MoneyBack';
		// $item->ReturnPolicy->ReturnsWithinOption = 'Days_14';
		// $item->ReturnPolicy->ShippingCostPaidByOption = 'Buyer';
		// $item->ShippingDetails = new Types\ShippingDetailsType();
		// $item->ShippingDetails->ShippingType = Enums\ShippingTypeCodeType::C_FLAT;
		//
		// $item->Quantity = (int)$listingItem->qty;
		// $item->StartPrice = new Types\AmountType(array('value' => (double)$listingItem->price));;
		// $item->BestOfferDetails = new Types\BestOfferDetailsType();
		// //return var_dump($listingItem->bestOffer);
		// if($listingItem->bestOffer!="false"){
		// 	$item->BestOfferDetails->BestOfferEnabled = true;
		// 	$item->ListingDetails = new Types\ListingDetailsType();
		// 	$item->ListingDetails->BestOfferAutoAcceptPrice = new Types\AmountType(array('value' => (double)$listingItem->bestOffer[0]));
		// 	$item->ListingDetails->MinimumBestOfferPrice = new Types\AmountType(array('value' => (double)$listingItem->bestOffer[1]));
		// }else{
		// 	$item->BestOfferDetails->BestOfferEnabled = false;
		// }
		//
		// $item->PrimaryCategory = new Types\CategoryType();
		// $item->PrimaryCategory->CategoryID = $listingItem->primaryCate;
		// $item->Title = $listingItem->title;
		// $item->Description = "<![CDATA[" . $listingItem->description . "]]>";
		// //$item->Description = "test";
		// $item->SKU = $listingItem->sku;
		// $item->Location = $listingItem->location;
		// $item->PictureDetails = new Types\PictureDetailsType();
		// $item->PictureDetails->GalleryType = Enums\GalleryTypeCodeType::C_GALLERY;
		// $item->PictureDetails->PictureURL = $listingItem->picture;
		// $item->PaymentMethods = array(
		//     'PayPal'
		// );
		// $item->PayPalEmailAddress = $listingItem->paypal;
		// $shippingService = new Types\ShippingServiceOptionsType();
		// $shippingService->ShippingServicePriority = 1;
		// $shippingService->ShippingService = $listingItem->shippingService;
		// if($listingItem->shippingCost=="true"){
		// 	$shippingService->FreeShipping = true;
		// }else{
		// 	$shippingService->ShippingServiceCost = new Types\AmountType(array('value' => (double)$listingItem->shippingCost[0]));
		// 	$shippingService->ShippingServiceAdditionalCost = new Types\AmountType(array('value' => (double)$listingItem->shippingCost[1]));
		// }
		//
		// $item->ShippingDetails->ShippingServiceOptions[] = $shippingService;
		$item = $this->buildItem($listingItem);
		$request->Item = $item;
		//$service = $this->tradingServiceInit();
		$service = new Services\TradingService(array(
	      'apiVersion' => EbayApi::COMPATABILITY_LEVEL,
	      'siteId' => EbayApi::SITE_ID,
	      'authToken' => $this->token,
	      'devId' => EbayApi::DEV_ID,
	      'appId' => EbayApi::APP_ID,
	      'certId' => EbayApi::CERT_ID,
	  ));
		$result['itemID'] = "123";
		//$result['error'][] = "123";
		//$result['error'][] = "fail";
		return $result;
		$response = $service->addFixedPriceItem($request);
		$result = [];
		if (isset($response->Errors)) {
		    foreach ($response->Errors as $error) {
					$result['error'][]=$error->ShortMessage;
		      $result['error'][]=$error->LongMessage;
		    }
		}
		if ($response->Ack !== 'Failure') {
		    $result['itemID'] = $response->ItemID;
		}

		return $result;
	}
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

	public function reviseItem($listingItem){
		$service = $this->tradingServiceInit();
		$request = new Types\ReviseFixedPriceItemRequestType();
		$request->RequesterCredentials = new Types\CustomSecurityHeaderType();
		$request->RequesterCredentials->eBayAuthToken = $this->token;
		$item = new Types\ItemType();
		$item->ItemID = $listingItem->item_id;
		$item->Description = "<![CDATA[" . $listingItem->description . "]]>";
		$item->StartPrice = new Types\AmountType(array('value' => (double)$listingItem->price));
		$item->Quantity = (int)$listingItem->qty;
		$item->Title = $listingItem->title;
		$item->ShippingDetails = new Types\ShippingDetailsType();
		$item->ShippingDetails->ShippingType = Enums\ShippingTypeCodeType::C_FLAT;
		$shippingService = new Types\ShippingServiceOptionsType();
		$shippingService->ShippingServicePriority = 1;

		if($listingItem->shippingCost[0]=="true"){
			$shippingService->FreeShipping = true;
			$shippingService->ShippingService = $listingItem->shippingCost[1];
		}else{
			$shippingService->ShippingServiceCost = new Types\AmountType(array('value' => (double)$listingItem->shippingCost[0]));
			$shippingService->ShippingServiceAdditionalCost = new Types\AmountType(array('value' => (double)$listingItem->shippingCost[1]));
			$shippingService->ShippingService = $listingItem->shippingCost[2];
		}

		$item->ShippingDetails->ShippingServiceOptions[] = $shippingService;
		if($listingItem->shippingExpressCost!="false"){
			$shippingServiceExpress = new Types\ShippingServiceOptionsType();
			$shippingServiceExpress->ShippingServicePriority = 2;
			$shippingServiceExpress->ShippingService = 'AU_ExpressDelivery';
			$shippingServiceExpress->ShippingServiceCost = new Types\AmountType(array('value' => (double)$listingItem->shippingExpressCost[0]));
			$shippingServiceExpress->ShippingServiceAdditionalCost = new Types\AmountType(array('value' => (double)$listingItem->shippingExpressCost[1]));
			$item->ShippingDetails->ShippingServiceOptions[] = $shippingServiceExpress;
		}

		$request->Item = $item;
		$response = $service->reviseFixedPriceItem($request);
		$result = [];
		if (isset($response->Errors)) {
		    foreach ($response->Errors as $error) {
					$result['error'][]=$error->ShortMessage;
		      $result['error'][]=$error->LongMessage;
		    }
		}
		if ($response->Ack !== 'Failure') {
		    $result['itemID'] = $response->ItemID;
		}

		return $result;
		//return $response;
	}
	public function getOneItem($itemID){
		$item = $this->getOneItemOnly($itemID);
		if($item!=='error'){
			$result = [
				'price'=>$item->Item->SellingStatus->CurrentPrice->value,
				'qty'=>$item->Item->Quantity-$item->Item->SellingStatus->QuantitySold,
				'title'=>$item->Item->Title,
				'sku'=>$item->Item->SKU,
				'qtySold'=>$item->Item->SellingStatus->QuantitySold,
			];
			$shippingServiceOptions = [
				[
					'shippingService'=>$item->Item->ShippingDetails->ShippingServiceOptions[0]->ShippingService,
					'shippingCost'=>$item->Item->ShippingDetails->ShippingServiceOptions[0]->ShippingServiceCost->value,
					'shippingAddCost'=>isset($item->Item->ShippingDetails->ShippingServiceOptions[0]->ShippingServiceAdditionalCost)?$item->Item->ShippingDetails->ShippingServiceOptions[0]->ShippingServiceAdditionalCost->value:0,
				],
			];
			if(isset($item->Item->ShippingDetails->ShippingServiceOptions[1])){
				$shippingServiceOptions[]=[
					'shippingService'=>$item->Item->ShippingDetails->ShippingServiceOptions[1]->ShippingService,
					'shippingCost'=>$item->Item->ShippingDetails->ShippingServiceOptions[1]->ShippingServiceCost->value,
					'shippingAddCost'=>isset($item->Item->ShippingDetails->ShippingServiceOptions[1]->ShippingServiceAdditionalCost)?$item->Item->ShippingDetails->ShippingServiceOptions[1]->ShippingServiceAdditionalCost->value:0,

				];
			}
			$result['shippingServiceOptions']=$shippingServiceOptions;
			return $result;
		}else{
			return "error";
		}
	}
	private function getOneItemOnly($itemID){
		$service = $this->tradingServiceInit();
		$itemRequest = $this->getItemReqInit();
		$itemRequest->ItemID = $itemID;
		$reqItem = $service->getItem($itemRequest);
		if ($reqItem->Ack !== 'Failure' && isset($reqItem->Item)) {
			return $reqItem;
			//return $reqItem->Item;
		}else{
			return "error";
		}
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
