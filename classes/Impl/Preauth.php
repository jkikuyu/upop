<?php
/*
 * @file    PurchaseCancel.php
 * @author  Jude
 * @date    02/01/2019
 * @version $Revision$
 * @Purpose Class to make purchase request
 */
namespace UnionPay;

use Dotenv\Dotenv;   
use UnionPay\UpopConf;

require_once(dirname(__dir__).'/Interfaces/IPaymentType.php');
require_once(dirname(__dir__).'/PaymentReq.php');


class Preauth extends PaymentReq implements IPaymentType{
	private $txntype;
	private $txnSubType;
	public function __construct(){

		$this->txntype=getenv('UPOP.PREAUTH.TXNTYPE');
		$this->txnSubType=getenv('UPOP.TXNSUBTYPE');
	}

	public function processRequest($merged_data=null, $requiredData=null){
		/*

		* i.  validate merged array
		* ii.   get keystore
		* iii. generate signature
		*/
		$oData = (object) $merged_data; //make object for validation 
		$isValid = parent::isRequestValid($oData,$requiredData);

		if ($isValid){
			$signature	= parent::getSignature($merged_data);
		}
		else{
			return $isValid;
		}
		return $signature;

	}	
	public function mergeData($defaultContent=null,$userData=null){
		$type = ["txnType"=>$this->txntype,"txnSubType"=>$this->txnSubType];
		$merged_data = parent::mergedData($defaultContent,$userData,$type);

		return $merged_data;
	}
	public function convertToString($merged_final=null){
		$urlEncode=true; 
		$strData = parent::convertToString($merged_final,$urlEncode);
		return $strData;
	}

	public function initiateRequest(array $reqData, $url, $port){
		
		$response = parent::curlPost($reqData, $url, $port);
		echo $response;


		
	}


}

?>