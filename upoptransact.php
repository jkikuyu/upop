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

$dataRecd = file_get_contents('php://input');

$isRequestJson = (json_decode($dataRecd) != NULL) ? true : false;
$logfile = Utils::getLogFile();
$log = new Logger('Upop');
$log->pushHandler(new StreamHandler($logfile , Logger::INFO));

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
				$url = $upopconf->frontTransUrl;
				// purchase

				break;
			case UpopConf::CANCELPURCHASE:
				$var = 'PurchaseCancel';
				$url = upopConf->backTransUrl;

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
	//use__NAMESPACE__ . '\\' . $var in variable before instantiating
	$class = __NAMESPACE__ . '\\' . $var;
	$classobj = new $class;
	$defaultContent = $upopconf->getDefaultContent();
	$merged = $classobj->mergeData($defaultContent, $json, $type = null);
	$requiredFlds = $upopconf->getRequiredFlds();

	$sort = ksort($merged);
	
	$signature = $classobj->processRequest($merged, $requiredFlds);
	//$certID = $upopconf->certid;
	//$certDetail = ["signature"=>$signature,"certId"=>$certID];
	$certDetail = ["signature"=>$signature];
	$merged_final= array_merge($merged,$certDetail);
	

	// $sorted = ksort($merged_final);

	$classobj->initiateRequest($merged_final,$url);
}

else{
	//Utils::logger(array("invalid JSON request"));
	$log->error("invalid JSON request");
	new \Exception ("invalid JSON request");

}
?>