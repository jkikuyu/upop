<?php
require_once('classesAutoload.php');
$headers = ["Content-type:application/x-www-form-urlencoded;charset=UTF-8"];
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
$url = 'https://gateway.test.95516.com/gateway/api/backTransReq.do';
$port = 443;
$timeout = 30;

$bizType="000000";


$data = "";
$pass = "000000";
$encSuccess = false;
$txnsubtype="01";
$orderid ="20190725090156";
$backUrl="http://222.222.222.222:8080/ACPSample_WuTiaoZhuan_Token/backRcvResponse";
$signature="";
$customerInfo = "e3Ntc0NvZGU9MTExMTExfQ==";
$txntype="01";
$channeltype="07";
$certId="69629715588";
$encoding="UTF-8";
$version = "5.1.0";
$accessType="0";
$encryptedCertId = "68759622183";
$txnTime = "20191001185834";
$orderid ="20191001185834";

$merchantID="000000070000017";
$currencyCode="156";
$signMethod="01";
$txnAmt ="1000";
$b64 = "";

if ($encfile = file_get_contents("file:///home/jkikuyu/ipay/upop/certs/test/acp_test_enc.cer")) {
	//echo "encryption cert";
	$encSuccess = true;
    $publickey = openssl_pkey_get_public($encfile);
	
    $keyData = openssl_pkey_get_details($publickey);
    $pubkey=$keyData['key'];
	//echo $pubkey;
    $card="6216261000000000018";
    openssl_public_encrypt($card, $accNo, $pubkey,OPENSSL_PKCS1_PADDING);   
    $accNo = base64_encode($accNo);
	//echo $accNo;
}
else{
    echo "Error: Unable to read the cert file\n";
    exit;
}
if (!$cert_store = file_get_contents("file:///home/jkikuyu/ipay/upop/certs/test/acp_test_sign.pfx")) {
	echo "Error: Unable to read the cert file\n";
	exit;
}

  $data= [
	   		"bizType"=>$bizType,
		   	"txnSubType"=>$txnsubtype,
	   		"orderId"=>$orderid,
            "backUrl"=>$backUrl,
	   		"accNo" =>$accNo,
	 	   	"customerInfo"=>$customerInfo,
	   		"txnType"=>$txntype,
	        "channelType"=>$channeltype,
	   		"certId"=>$certId,
	        "encoding"=>$encoding,
	   		"version"=>$version,
            "accessType"=>$accessType,
	   		"encryptCertId"=>$encryptedCertId,
	   		"txnTime"=>$txnTime,
            "merId"=>$merchantID,
            "currencyCode"=>$currencyCode,
	        "signMethod" =>$signMethod, 
		   	"txnAmt"=>$txnAmt
		   ];

			ksort($data);

 			$orig = $data;
			$str="";
		 	foreach($data as $key => $value) {
				$str.= $key."=";
					
					$str.= $value."&";

            }
			$len = strlen($str);
			$len -=1;
			$str = substr($str, 0, $len);

		echo "string to be signed: ".$str;

			if (openssl_pkcs12_read($cert_store, $certs, $pass)) {
			
			$utf8=   utf8_encode ($str);
			//echo "<br />UTF 8: <br />". $utf8;
			$sha256 = hash ("sha256",$utf8);
			
			//echo "<br /><br />" .$sha256;
			$utf8_1=   utf8_encode ($sha256);
			echo "<br />utf8: ". $utf8_1. "<br />";
			$privateKey = $certs['pkey'];
			if (openssl_sign ( $utf8_1 , $signature ,  $privateKey, "sha256WithRSAEncryption" )){
				$b64 = base64_encode($signature);

			}
			else{
				echo "error in signing";
			}
		}
		$keys = array_keys( $orig );
    	$keys[ array_search( "accNo", $keys ) ] = "signature";
		$data=array_combine( $keys, $orig);
		$data["signature"]=$b64;

		$strData=""; 
            foreach($data as $key => $value) {
				$strData.= $key."=";
					
					$strData.= urlencode($value);
				
					$strData.="&";

            }
        $strData = substr($strData,0,strlen($strData)-1);

		echo "<br />string to send :<br />".$strData;
	$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		//set request headers
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_PORT, $port);

		//request method is POST
		curl_setopt($curl, CURLOPT_POST, 1);
		//request body
		curl_setopt($curl, CURLOPT_POSTFIELDS, $strData);


		//return transfer response as string to the $curl resource
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		//output verbose info
		curl_setopt($curl, CURLOPT_VERBOSE, 1);

		$output = curl_exec($curl);

		echo "result <br />".$output;




function decryptCard($package, $cert_store){
	$pass = "000000";
	openssl_pkcs12_read($cert_store, $certs, $pass);
	$res=$certs['pkey'];
	$b64dec = base64_decode($package); 

	openssl_private_decrypt($b64dec,$decrypted,$res);
	echo "plain text ". $decrypted;

}


?>
