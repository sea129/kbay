<?php

namespace frontend\components\ebayapi;

use Yii;
use frontend\models\ebayaccounts\EbayAccount;
use DTS\eBaySDK\Constants;
use DTS\eBaySDK\Trading\Services;
use DTS\eBaySDK\Trading\Types;
use DTS\eBaySDK\Trading\Enums;
use yii\web\NotFoundHttpException;
/**
 * trading api
 */
class EbayApi
{
	const COMPATABILITY_LEVEL = 941;

	const DEV_ID = 'fd36a6b5-8f8c-4595-a565-8b2bd583e7ac';

	const APP_ID = 'jhcfa367d-11b7-4d4e-8293-0fb8e006303';

	const CERT_ID = '42e7ef5c-f56f-457d-b95e-b9da2dd6aa64';

	const LOGIN_URL = 'https://signin.ebay.com.au/ws/eBayISAPI.dll';

	const RUNAME = 'jhc-jhcfa367d-11b7--jiwei';

	const SERVER_URL = 'https://api.ebay.com/ws/api.dll';

	const SITE_ID = 15;

	protected $token;

	//public $ebayID;

	public function __construct($ebayID = null){
		if($ebayID!==null && $ebayAcc = EbayAccount::findOne(['id'=>$ebayID,'user_id'=>Yii::$app->user->id])){
			$this->token = $ebayAcc->token;
		}else{
			throw new NotFoundHttpException('The requested page does not exist.');
			$this->token = null;
		}
		//$this->tokens = [];
		// $this->ebayID = $ebayID;
		// if($this->ebayID == null){//所有ebay
		// 	$ebayAccArray = EbayAccount::find()->allOfCurrentUser($db = null);
		//
		// 	foreach ($ebayAccArray as $ebayAccModel) {
		// 		$this->tokens[$ebayAccModel->seller_id] = $ebayAccModel->token;
		// 	}
		// }else{//一个ebay
		// 	$ebayAcc = EbayAccount::findOne(['id'=>$this->ebayID,'user_id'=>Yii::$app->user->id]);
		// 	$this->tokens=$ebayAcc->token;
		// }
		//$this->tokens='1';
	}

	public function getToken($theID)
	{
		$verb = 'FetchToken';
		$requestBody = '<?xml version="1.0" encoding="utf-8" ?>';
        $requestBody .= '<FetchTokenRequest xmlns="urn:ebay:apis:eBLBaseComponents">';

        $requestBody .= "<SessionID>$theID</SessionID>";
        $requestBody .= '</FetchTokenRequest>';

        $sessN = new EbaySession(self::DEV_ID, self::APP_ID, self::CERT_ID, self::SERVER_URL, self::COMPATABILITY_LEVEL, self::SITE_ID, $verb);

        $responseBody = $sessN->sendHttpRequest($requestBody);

        if(stristr($responseBody, 'HTTP 404') || $responseBody == '')
			throw new NotFoundHttpException();

		$resp = simplexml_load_string($responseBody);
		$token = (string)$resp->eBayAuthToken;
		$expirationTime = (string)$resp->HardExpirationTime;
		if($resp->Ack=='Success')
        {
        	return [$token,$expirationTime];
        }else{
        	return false;
        }

	}

	public function getSessionID(){
		$verb = 'GetSessionID';

    	$requestBody = '<?xml version="1.0" encoding="utf-8" ?>';
		$requestBody .= '<GetSessionIDRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
		$requestBody .= "<RuName>".self::RUNAME."</RuName>";
		$requestBody .= '</GetSessionIDRequest>';

		$sessN = new EbaySession(self::DEV_ID, self::APP_ID, self::CERT_ID, self::SERVER_URL, self::COMPATABILITY_LEVEL, 15, $verb);

    	$responseBody = $sessN->sendHttpRequest($requestBody);

    	if(stristr($responseBody, 'HTTP 404') || $responseBody == '')
			throw new NotFoundHttpException();

		$resp = simplexml_load_string($responseBody);

		$sesId = (string)$resp->SessionID;
        /*$session->set('ebSession',(string)$resp->SessionID);
        $sesId = urlencode($session->get('ebSession'));*/

        if($resp->Ack=='Success')
        {
        	return ['sesId'=>$sesId,'loginURL'=>self::LOGIN_URL.'?SignIn&runame='.self::RUNAME.'&SessID='];
        }else{
        	return false;
        }
	}
	protected function tradingServiceInit(){
		return new Services\TradingService(array(
		    'apiVersion' => self :: COMPATABILITY_LEVEL,
		    'siteId' => self :: SITE_ID,
		));
	}
	protected function getOfficialTime(){
		$service = $this->tradingServiceInit();
    $request = new Types\GeteBayOfficialTimeRequestType();
    $request->RequesterCredentials = new Types\CustomSecurityHeaderType();
    $request->RequesterCredentials->eBayAuthToken = $this->token;
    $response = $service->geteBayOfficialTime($request);
    return $response->Timestamp;
	}

	protected function getResponseError($response){
		$result = [];
		if (isset($response->Errors)) {
				foreach ($response->Errors as $error) {
					//$result[($error->SeverityCode === Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning')][]=['ShortMessage'=>$error->ShortMessage,'LongMessage'=>$error->LongMessage,];
					$result['Error'][]=$error->ShortMessage;
				}

		}
		return $result;
	}
}

 ?>
