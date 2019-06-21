<?php

$url = 'https://gateway.test.95516.com/gateway/api/backTransReq.do';
$port = 443;
$timeout = 30;
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

$data = 
	
$pass = "000000";
if (!$cert_store = file_get_contents("file:///home/jkikuyu/ipay/upop/certs/test/acp_test_sign.pfx")) {
    echo "Error: Unable to read the cert file\n";
    exit;
}


$headers = ["Content-type:application/x-www-form-urlencoded;charset=UTF-8"];
$version = "5.1.0";
$encoding="UTF-8";
$signMethod="01";
$bizType="000000";
$accessType="0";
$merchantID="000000070000017";
$currencyCode="156";
//$payTimeOut
//$backUrl="https://165.227.173.79/upop/back_notify.php";
$backUrl="http://222.222.222.222:8080/ACPSample_WuTiaoZhuan_Token/backRcvResponse";
$txnAmt ="1000";

$txntype="01";
$txnsubtype="01";
$txnTime = "20190621143842";
$orderid ="20190621143842";
//$qryid = "777290058110048";
$txntime="20190621143842";

$certId = "69629715588";

$accNo = "FWRxIO0OdBP6JeU6/PPeYJKCOdv5rCIhmUUW/dsYX5Abe0bEUNgJNBRv8QoNkeEdpeu9/jWsgw2zzDXvpkZ81r0hOwxyz1+/kim3EumalCwtBWZrJvO9TXkxELPni5Tpem+4udg/rdWPgBi+2/M2igtJ/XihNs36p0amFT0RXwtRRvdvz8g3bz7dOq2Cf7+OWDtOrDN5sFdXfXo5sivehQha9dhu+mLOTep2vqoTpEr3Re4yPjkrigCI5Z9HEdi101S7z8Rw1OTbzbDxj4NNhAB49rIXGEybgeTNVGkHURFy2HbpTpNdbJYKAAv5vLkWtvtA/WNzciD+ict8DHfzcw==";
$encryptCertId="68759622183";
$customerInfo = "e3Ntc0NvZGU9MTExMTExfQ==";
$channeltype="07";

   $data= [
	   		"bizType"=>$bizType,
		   	"txnSubType"=>$txnsubtype,
	   		"orderId"=>$orderid,
            "backUrl"=>$backUrl,
	   		"accNo" =>$accNo,
	   		"encryptCertId"=>$encryptCertId,
	   		"txnType"=>$txntype,
	        "channelType"=>$channeltype,
	   		"certId"=>$certId,
	        "encoding"=>$encoding,
	   		"version"=>$version,
            "accessType"=>$accessType,
	   		"txnTime"=>$txntime,
            "merId"=>$merchantID,
            "currencyCode"=>$currencyCode,
	        "signMethod" =>$signMethod, 
	   		"customerInfo"=>$customerInfo,
		   	"txnAmt"=>$txnAmt
		   ];
     //       "payTimeout"=> $payTimeOut,
	   
      //      "fronturl"=>$frontUrl,
			ksort($data);

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
			$sha256 = hash ("sha256",$utf8);
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
		echo "<br /><br />";
		$sig = ["signature"=>$b64];
		echo "Signature : ". $b64;
		$data = array_merge($data,$sig);
		$strData=""; 
            foreach($data as $key => $value) {
				$strData.= $key."=";
					
					$strData.= urlencode($value);
				
					$strData.="&";

            }
        $strData = substr($strData,0,strlen($strData)-1);
//		echo $strData;


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



//		echo $strData;

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
