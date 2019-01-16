<?php
/*

/**
i. Receive order id , order time and txn from the web page

ii. Combine these together with values held in the properties file and certid obtained from certificate to generate a string (e.g. accessType=0&backUrl=https://165.227.173.79/upop/back_notify.php&bizType=000201&certId=68759663125&channelType=07&currencyCode=156&encoding=UTF-8&frontUrl=https://165.227.173.79/upop/front_notify.php&merId=777290058110048&orderId=20190102081504&signMethod=01&txnAmt=1000&txnSubType=01&txnTime=20190102081504&txnType=01&version=5.1.0)

iii. ensure the string is UTF-8 by encoding it to UTF-8

iv. convert to a byte array and hash using sha256

v Encode the resulting hash UTF-8 and convert to byte array.

vi. sign the byte array using private key and algorithm SHA256withRSA

vii Encode the resulting signed data using base64 and convert to string

viii. Combine this with the above string to get a string (e.g. bizType=000201, txnSubType=01, backUrl=https://165.227.173.79/upop/back_notify.php, signature=K3WaXiHevEuj0OuEqW6I8JrZrLW5rP+7VZIft4hABljcnIDC5W8uHnL8DWSprVTO621f5+VZHBjllSw8klWV4Zn31ngNsstROKG9jsjQfe0Nc1RQo5ssfWf780Y3vdA5KCsZhgSXI24kIWDjuu4pvssdj8QUmAFn+pFtAXQCYPNcadw03Ff7GyN9l/JQL6yq24S6LXcbOQV/bn9f4qvCamSQ3/isRtDFrctCee6e4hhGWaFx6fkK9ldLR0CgZtkPE7+WJsLgl/+TnwjJiTWqM1Fc64m15vvBCBUthpdO7/9W1UwfHwoTqMBTD47aXihephKV1RuM1NDVy/t9C4JlUg==, txnType=01, channelType=07, frontUrl=https://165.227.173.79/upop/front_notify.php, certId=68759663125, encoding=UTF-8, version=5.1.0, accessType=0, currencyCode=156, signMethod=01)

ix. Forward this string to test server.
*/


$data="accessType=0&backUrl=https://165.227.173.79/upop/back_notify.php&bizType=000201&certId=68759663125&channelType=07&currencyCode=156&encoding=UTF-8&merId=777290058110048&orderId=20190108123512&origQryId=777290058110048&signMethod=01&txnAmt=1000&txnSubType=00&txnTime=20190108123512&txnType=31&version=5.1.0";
$pass = "000000";
if (!$cert_store = file_get_contents("file:///home/jkikuyu/ipay/upop/certs/test/acp_test_sign.pfx")) {
    echo "Error: Unable to read the cert file\n";
    exit;
}

if (openssl_pkcs12_read($cert_store, $certs, $pass)) {
    $utf8=   utf8_encode ($data);
/*
	$a=array_merge($certs);
	$b=$a['cert'];
*/
    $sha256 = hash ("sha256",$utf8);
	//echo "length: " . strlen($sha256);
	//echo "hex:[" .$sha256. "]";
	//$string = hex2bin($sha256);
	$utf8_1=   utf8_encode ($sha256);
//var_dump($string);
	//$test = unpack('c*', $utf8_1);
	//print_r($test);
 //   $data = unpack("n*",$sha256);
/*
    $str = pack("n*",$data);
    var_dump($str);
*/    
    $privateKey = $certs['pkey'];
	//$sn = openssl_x509_fingerprint ($b, "sha256");
	//echo $sn."  ";
	
	//echo hexdec($sn);


/*
	$str = "";
	 for($i=0;$i<strlen($sn);$i+=2)
       $str .= chr(hexdec(substr($sn,$i,2)));
	echo $str;
*/

	//echo $serialnumber;
    //echo $privateKey;
 
    //echo "Certificate Information\n";

    if (openssl_sign ( $utf8_1 , $signature ,  $privateKey, "sha256WithRSAEncryption" )){
		//echo "signature: ";
        //print_r($signature);
		//$result = unpack('c*', $signature);
        $b64 = base64_encode($signature);
		echo " base 64 :" . $b64;
		//$final = unpack('c*', $b64);
		//print_r($final);

    }
    else{
        echo "error in signing";
    }
    /*if (openssl_pkcs12_export_to_file($cert_store, "file:///home/jkikuyu/ipay/upop/certs/test/priv.key",$privateKey,$pass)){
        echo "success";
    }*/
   // print_r($privateKey);
    
} else {
    echo "Error: Unable to read the cert store.\n";
    exit;
}




?>