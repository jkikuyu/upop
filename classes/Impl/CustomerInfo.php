<?php
namespace UnionPay;
/**
**@author Jude
**date:01/01/2019
**/

require_once(dirname(__dir__).'/Interfaces/ICustomerInfo.php');
require_once(dirname(__dir__).'/PaymentReq.php');
class CustomerInfo extends PaymentReq implements ICustomerInfo{
	public function __construct(){
	}
	
	public function encryptCard($card_number){
		$keystore = CertUtils::getKeyStore();
		var_dump($keystore);
		$pkey = $keystore['pkey'];
		$signedData = CertUtils::generateSignature($pkey, $card_number);
	return $signedData;	
	}
}

?>
