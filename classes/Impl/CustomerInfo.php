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
		$encryptCert= CertUtils::getInstance()->getEncryptCertPath();

        $publickey = openssl_pkey_get_public($encryptCert);
        $keyData = openssl_pkey_get_details($publickey);
	    $key=$keyData['key'];
		openssl_public_encrypt($card_number, $enc, $key, OPENSSL_PKCS1_PADDING);   
    	$cardEnc = base64_encode($enc);
		//echo "card enc: ".$cardEnc;
	return $cardEnc;	
	}
	public function encryptedCertId(){
		$encryptCert= CertUtils::getInstance()->getEncryptCertPath();
		
		$data=openssl_x509_parse($encryptCert,true);
		$serialNo = $data['serialNumber'];
		echo "encrypted cert id ". $serialNo;
	return $serialNo;

		
	}
	public function encryptCustomerInfo(array $customerInfo, $card_number){
		foreach ($customerInfo as $key=>$value){
			if($key==="phoneNo" || $key==="cvn2" || $key==="expired"){
				$strData.= $key."=".$value."&";


			}
			else{ 
				if ($key==="pin" && strlen(trim($card_number))>0){

				}
				$strData = $key."=".$value."&";
			}

		}
	    $strData = "{" . substr($strData,0,strlen($strData)-1) . "}";

	    $customerInfoEnc = base64_encode($strData);
		//echo "customer info enc:" . $customerInfoEnc;
	 return $customerInfoEnc;
	}
}

?>
