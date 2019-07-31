<?php
require_once('classesAutoload.php');
include('Crypt/RSA.php');
$url = 'https://gateway.test.95516.com/gateway/api/backTransReq.do';
$port = 443;
$timeout = 30;
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);


$data = "";
$pass = "000000";
$encSuccess = false;

if (!$cert_store = file_get_contents("file:///home/jkikuyu/ipay/upop/certs/test/acp_test_sign.pfx")) {
    echo "Error: Unable to read the cert file\n";
    exit;
}

$rsa = new Crypt_RSA();
$rsa->loadKey('file:///home/jkikuyu/ipay/upop/certs/test/acp_test_enc.cer'); // public key
$card="6216261000000000018";

//$plaintext = '...';

$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
$ciphertext = $rsa->encrypt($card);
echo "cipher text ". $ciphertext
/*if ($encfile = file_get_contents("file:///home/jkikuyu/ipay/upop/certs/test/acp_test_enc.cer")) {
	//echo "encryption cert";
	$encSuccess = true;
    $publickey = openssl_pkey_get_public($encfile);
    $keyData = openssl_pkey_get_details($publickey);
    $pubkey=$keyData['key'];
    $card="6216261000000000018";
    openssl_public_encrypt($card, $accNo, $pubkey);   
    $accNo = base64_encode($accNo);
    echo "encrypted string :::::<br />".$accNo. "<br />";
}
else{
    echo "Error: Unable to read the cert file\n";
    exit;
}*/


$headers = ["Content-type:application/x-www-form-urlencoded;charset=UTF-8"];
$bizType="000000";

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
$txnTime = "20190725090156";

$merchantID="000000070000017";
$currencyCode="156";
$signMethod="01";
$txnAmt ="1000";

//$qryid = "777290058110048";




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
     //       "payTimeout"=> $payTimeOut,
	   
      //      "fronturl"=>$frontUrl,
			$orig = $data;
	//		ksort($data);
			$str="";
		 	foreach($data as $key => $value) {
				$str.= $key."=";
					
					$str.= $value."&";

            }
			$len = strlen($str);
			$len -=1;
			$str = substr($str, 0, $len);
			$accNo = "";
			//echo "<br /> data to sign<br />". $str;

/*
			$str = "accNo=LTMc6ZBnSS4gvYg81Q6MPJvDCwNi2laQ8o5QPAH5wV+ns2oJqGm5tthIpgI+Z+xxVwNHwxUzzn3UhRa3jeyoSCad2BgnYSgJnVQOjn3kSMIgKhte279Tlg4+h644Akrmb8cUeeK1/TwYI2urDSvSy3eymQ6oORSy3RfQJbWcxEK+Q3qgIW2L1M63PSU8tw9OORYrAX7hYqR6B+rTAPwFI1Oz7swDrcCkbUXiQIsW+o347SasU4DgDLCR2M/NZ0pBt0QGsa6NpccB/K9VzDuLkehvgyWlaGmwnAn87mK9H2QBUsrEiaYvRNio3EiCyOxtkziy7iHBZDEVCW1nBkgLkw==&accessType=0&backUrl=http://222.222.222.222:8080/ACPSample_WuTiaoZhuan_Token/backRcvResponse&bizType=000000&certId=69629715588&channelType=07&currencyCode=156&customerInfo=e3Ntc0NvZGU9MTExMTExfQ==&encoding=UTF-8&encryptCertId=68759622183&merId=000000070000017&orderId=20190717132446&signMethod=01&txnAmt=1000&txnSubType=01&txnTime=20190717132446&txnType=01&version=5.1.0";
*/
		//echo "string to be signed: ".$str;
        if ($encSuccess) {
/*            $publickey = openssl_pkey_get_public($encfile);
            $keyData = openssl_pkey_get_details($publickey);
			$key=$keyData['key'];
            $card="6216261000000000018";
            openssl_public_encrypt($card, $accNo, $key); */  
            echo "<br />encrypted string :::::".$accNo;
        }
        else{
            echo "error in public key";
        }

		if (openssl_pkcs12_read($cert_store, $certs, $pass)) {
			
			$utf8=   utf8_encode ($str);
			echo "<br />UTF 8: <br />". $utf8;
			$sha256 = hash ("sha256",$utf8);
			
			echo "<br /><br />" .$sha256;
			$utf8_1=   utf8_encode ($sha256);
			$privateKey = $certs['pkey'];
			if (openssl_sign ( $utf8_1 , $signature ,  $privateKey, "sha256WithRSAEncryption" )){
				$b64 = base64_encode($signature);
				//echo " base 64 :" . $b64;
				//$final = unpack('c*', $b64);
				//print_r($final);

			}
			else{
				echo "error in signing";
			}
		}
		//echo "<br />b64 :<br />".$b64."<br />";
		//echo "<br /><br />";
		//$sig = ["signature"=>$b64];
		//echo "<br />Signature : ". $b64;
		//$data = array_merge($data,$sig);
    	$keys = array_keys( $orig );
    	$keys[ array_search( "accNo", $keys ) ] = "signature";
		$data=array_combine( $keys, $orig);
		$data["signature"]=$b64;
		//unset($data["accNo"]);
		//var_dump($data);

		$strData=""; 
            foreach($data as $key => $value) {
				$strData.= $key."=";
					
					$strData.= urlencode($value);
				
					$strData.="&";

            }
        $strData = substr($strData,0,strlen($strData)-1);
		echo "<br />string to send :<br />".$strData;

//		$context = stream_context_create( array (
//			
//		
//			'http' => array ( /* your options here - eg: */ 
//				'method' => 'POST',	
//				'header'=>"Content-type:application/x-www-form-urlencoded;charset=UTF-8", 
//				'content'=>$strData
//			),
//			
//			/* 'https' => 'DON'T forget there is no "https", only "http" like above', */
//			'ssl'  => array ( // here comes the actual SSL part...
//				'verify_peer'      => false,
//				'verify_peer_name' => false
//			)
//		) );
//
//		//$fp = fopen($url, 'r', false, $context);
//		//fpassthru($fp);
//		$result = file_get_contents($url, false, $context);
//		//$resp = file_get_contents($url);
//
//		echo "result <br />".$result;



		//echo $strData;


/*
	$strData= "bizType=000000&txnSubType=01&orderId=20190717132446&backUrl=http%3A%2F%2F222.222.222.222%3A8080%2FACPSample_WuTiaoZhuan_Token%2FbackRcvResponse&signature=tNetk1PgMyQCAm3Ak%2BqpXOxvhUyKjI1BvYMezXS0H3BYwM6pp7zw1PkaChQUtH%2FILhYWJfjyh2y1PZwoZeI%2FKLaLM1hddeGRWhzlnHb7NPj5I1Ew3%2F0XAfBSo2%2B%2BBoVc2LFeSPDILzpVtlvHqXLeGVvg8tlGr0jt4d1l8zx9KtKgG7t5m4J53bVYexP%2BHelonfuuwEmyV%2FCi%2B%2FMlHHYuIDepO2JuAcZTUAK67VmYCJWtXjSZ38anl%2ByRvQu%2Bp%2Fzu%2BlHIVEv5tojnCsFy8MdjCIQm%2BQ53Z8Wjala1G3fafAukrdN3v4AHEu87fRUJrucym%2F9JNROiE5yPH%2FD%2Fghkk8Q%3D%3D&accNo=LTMc6ZBnSS4gvYg81Q6MPJvDCwNi2laQ8o5QPAH5wV%2Bns2oJqGm5tthIpgI%2BZ%2BxxVwNHwxUzzn3UhRa3jeyoSCad2BgnYSgJnVQOjn3kSMIgKhte279Tlg4%2Bh644Akrmb8cUeeK1%2FTwYI2urDSvSy3eymQ6oORSy3RfQJbWcxEK%2BQ3qgIW2L1M63PSU8tw9OORYrAX7hYqR6B%2BrTAPwFI1Oz7swDrcCkbUXiQIsW%2Bo347SasU4DgDLCR2M%2FNZ0pBt0QGsa6NpccB%2FK9VzDuLkehvgyWlaGmwnAn87mK9H2QBUsrEiaYvRNio3EiCyOxtkziy7iHBZDEVCW1nBkgLkw%3D%3D&customerInfo=e3Ntc0NvZGU9MTExMTExfQ%3D%3D&txnType=01&channelType=07&certId=69629715588&encoding=UTF-8&version=5.1.0&accessType=0&encryptCertId=68759622183&txnTime=20190717132446&merId=000000070000017&currencyCode=156&signMethod=01&txnAmt=1000";
*/

	
/*
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

*/

		//var_dump($output);





/*

$data = http_build_query($strData);
echo "                      ".$data;
*/

            //init curl


?>
