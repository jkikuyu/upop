<?php

$url = 'https://gateway.test.95516.com/gateway/api/backTransReq.do';
$port = 443;
$timeout = 30;

$data = "accessType=0&backUrl=https://165.227.173.79/upop/back_notify.php&bizType=000201&certId=68759663125&channelType=07&currencyCode=156&encoding=UTF-8&merId=777290058110048&orderId=20190108232209&origQryId=777290058110048&signMethod=01&txnAmt=1000&txnSubType=00&txnTime=20190108232209&txnType=31&version=5.1.0";
$pass = "000000";
if (!$cert_store = file_get_contents("file:///home/jkikuyu/ipay/upop/certs/test/acp_test_sign.pfx")) {
    echo "Error: Unable to read the cert file\n";
    exit;
}

if (openssl_pkcs12_read($cert_store, $certs, $pass)) {
    $utf8=   utf8_encode ($data);
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
$headers = ["Content-type:application/x-www-form-urlencoded;charset=UTF-8"];
$version = "5.1.0";
$encoding="UTF-8";
$signMethod="01";
$bizType="000201";
$accessType="0";
$merchantID="777290058110048";
$currencyCode="156";
//$payTimeOut
$backUrl="https://165.227.173.79/upop/back_notify.php";
$txnAmt ="1000";


$txntype="31";
$txnsubtype="00";
$txnTime = "20190107233948";
$certId = "68759663125";
$orderid ="20190107233948";
$qryid = "777290058110048";
$txntime="20190107233948";
$channeltype="07";
   $data= [
	   		"bizType"=>$bizType,
		   	"txnSubType"=>$txnsubtype,
	   		"orderId"=>$orderid,
            "backurl"=>$backUrl,
	   	   	"signature"=>$b64,

	   		"txnType"=>$txntype,
	        "channelType"=>$channeltype,
	   		"certId"=>$certId,
	        "encoding"=>$encoding,
	   		"version"=>$version,
            "accessType"=>$accessType,
	   		"txnTime"=>$txntime,
            "merId"=>$merchantID,
	   		"origQryId"=>$qryid,
            "currencyCode"=>$currencyCode,
	        "signMethod" =>$signMethod, 
		   	"txnAmt"=>$txnAmt
		   ];
     //       "payTimeout"=> $payTimeOut,
	   
      //      "fronturl"=>$frontUrl,
	
		$strData="";
            foreach($data as $key => $value) {
				$strData.= $key."=";
					
					$strData.= urlencode($value);
				
					$strData.="&";

            }
        $strData = substr($strData,0,strlen($strData)-1);
		echo $strData;

		$context = stream_context_create( array (
			
		
			'http' => array ( /* your options here - eg: */ 
				'method' => 'POST',	
				'header'=>"Content-type:application/x-www-form-urlencoded;charset=UTF-8", 
				'content'=>$strData
			),
			
			/* 'https' => 'DON'T forget there is no "https", only "http" like above', */
			'ssl'  => array ( // here comes the actual SSL part...
				'verify_peer'      => false,
				'verify_peer_name' => false
			)
		) );

		$fp = fopen($url, 'r+', false, $context);
		fpassthru($fp);
		$resp = file_get_contents($url);
		fclose($fp);
		echo $resp;

/*
		//echo $strData;
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
		var_dump($output);
*/



//$data = http_build_query($strData);
//echo "                      ".$data;

            //init curl


?>