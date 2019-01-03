<?php
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
	
	$required_data = $upopconf->getRequiredData();

	$requiredUserData = $upopconf->getRequiredUserData();
	$json = json_decode($dataRecd);
	$isValid = Utils::validateRequest($json,$requiredUserData);
	if ($isValid){

		$class = null;
		switch ($json->type){
			case UpopConf::PURCHASE:
				$class = 'Purchase';
				// purchase

				break;
			case UpopConf::CANCELPURCHASE:
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
	//also possible __NAMESPACE__ . '\\' . $class . 'Class';
	$var = __NAMESPACE__ . '\\' . $class;
	$bar = new $var;
	$bar->test();
}
else{
	//Utils::logger(array("invalid JSON request"));
	$log->error("invalid JSON request");
	new \Exception ("invalid JSON request");

}



?>