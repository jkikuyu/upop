<?php
/*
 * @file    upoptransact.php
 * @author  Jude
 * @date    27/12/2018
 * @version $Revision$
 *
 */

namespace UnionPay;


use Monolog\Logger;
use Monolog\Handler\StreamHandler;

require_once('classesAutoload.php');
//require_once(dirname(__FILE__).'/classes/Impl/Purchase.php');
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
Utils::getLogFile("upop");
$dataRecd = file_get_contents('php://input');
//$dataRecd='{"type":"1", "card":"6216261000000000018","orderId":"20191008132716", "txnAmt":"1", "txnTime":"20191008132716"}';
$isRequestJson = (json_decode($dataRecd) != NULL) ? true : false;

if ($isRequestJson){

	$upopconf = new UpopConf();
	$required_data = $upopconf->getRequiredFlds();

	$requiredUserData = $upopconf->getRequiredUserInputs();
	$json = json_decode($dataRecd);
	$isValid = Utils::validateRequest($json,$requiredUserData);

	if ($isValid){

		$class = null;
		switch ($json->type){
			case UpopConf::PURCHASE:
				$var = 'Purchase';
				$url = $upopconf->backTransUrl;
				// purchase

				break;
			case UpopConf::CANCELPURCHASE:
				$var = 'PurchaseCancel';
				$url = $upopconf->backTransUrl;

				//purchase Cancel
				break;
			case UpopConf::REFUND:
				// refund
				break;
			case UpopConf::PREAUTH:
				//PreAuth
				break;
			case UpopConf::CANCELPREAUTH:
				//PreAuth Cancel
				break;
			case UpopConf::COMPLETEPREAUTH:
				//PreAuth Complete
				break;

			case UpopConf::CANCELCOMPLETEPREAUTH:
				//PreAuth Complete Cancel
				break;

			case UpopConf::RECURRING:
				//Recurring
				break;

			default:
				new \Exception ("invalid request");

		}
	}
	else{
		$log->error("invalid JSON request");
		new \Exception ("invalid JSON request");
	}
	
	$custInfo = new CustomerInfo();
	$card = $json->card;
	$customerInfo = ["smsCode"=>  $upopconf->smsCode];
	$encryptedCard = $custInfo->encryptCard($card);
	$encryptedCertId = $custInfo->encryptedCertId();
	$encryptedCustomerInfo =  $custInfo->encryptCustomerInfo($customerInfo,$json->card);
	$customerData = ["accNo"=>$encryptedCard, 'encryptCertId'=>$encryptedCertId,'customerInfo'=>$encryptedCustomerInfo];
	//echo "encrypted card: ".$encryptedCard;
	//use __NAMESPACE__ . '\\' . $var in variable before instantiating
	$class = __NAMESPACE__ . '\\' . $var;
	$classobj = new $class;
	$defaultContent = $upopconf->getDefaultContent();
	//print_r($defaultContent);
	$defaultContent=array_merge($defaultContent,$customerData);
	$merged = $classobj->mergeData($defaultContent, $json, $type = null);
	$requiredFlds = $upopconf->getRequiredFlds();
	$sort = ksort($merged);
	//var_dump($merged);
	$signature = $classobj->processRequest($merged, $requiredFlds);
	//echo "signature: ". $signature;


	//$certID = $upopconf->certid;
	//$certDetail = ["signature"=>$signature,"certId"=>$certID];
	$certDetail = ["signature"=>$signature];
	$merged_final= array_merge($merged,$certDetail);
	//print_r($merged_final);

	// $sorted = ksort($merged_final);
	$port = $upopconf->port;

	$data = $classobj->initiateRequest($merged_final,$url,$port);
	$ares = explode("&",$data);
	//Svar_dump($ares);
	$resp="";
	foreach( $ares as $item){
		$temp = explode("=",$item);
		$key=$temp[0];
		$value=$temp[1];
		$resp[$key] =  $value;

	}

	foreach($resp as $key => $value ){
	   if (  $key==='signPubKeyCert'){
		   $pubcertStr=$value;
		   break;
	   }
	}
	$validCert = $custInfo->isPubKeyCertValid($pubcertStr);
	if ($validCert){
		$respCode = $resp['respCode'] ;
		if ($respCode =='00'){
			echo '{
				"status":"200",
				"description":"OK",
				"respCode":"'.$respCode.'"

			}';
			
		}
		else{
			echo '{
				"status":"400",
				"decription":"failed",
				"respCode":"'.$respCode.'"
				
			}';
		}
		
	}
	else{
		die("certificate not valid");
	}
	
}
else{
	//Utils::logger(array("invalid JSON request"));
	Utils::infoMsg("invalid JSON request");;
	new \Exception ("invalid JSON request");

}
?>