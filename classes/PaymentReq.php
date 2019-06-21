<?php
namespace UnionPay;
/**
**@author Jude
**date:01/01/2019
**/
require_once(dirname(__dir__).'/classes/Interfaces/IPaymentReq.php');

require_once(dirname(__dir__).'/utils/CertUtils.php');

class PaymentReq implements IPaymentReq{
/**	private $orderID;
	private $txnTime;
	private $txnAmt;
**/
	public function assignValues($dataRecd){
		/*
		 * funciton to validated values 
		*/
        $assVal = array("orderid"=>$dataRecd->orderId,
            "txnTime"=>$dataRecd->txnTime,
		    "txnAmt"=>$dataRecd->txnAmt
		);

		return $assVal;
	}

    public function isRequestValid($recd=null, $required=null){
        /**
        
        @param recd data received from requester
        @param required fields required for processing
        
        **/
        $valid = false;
        try{
            if(empty($recd)){
                throw new \InvalidArgumentException('');
            }
            $valid = Utils::validateRequest($recd, $required);
        }
        catch(InvalidArgumentException $e){
           	$this->log->error($e . 'Invalid Request made');
        }
        return $valid;
    }

    public function convertToString($recd=null){
        $strData = null;
        ksort($recd);
/*<<<<<<< HEAD
            foreach($reced as $key => $value) {
				$strData.= $key."=";
				if (urlEncode)
					
					$strData.= urlencode($value);
				else
					$strData.= $value;
				
				$strData.="&";
=======*/
            foreach($recd as $key => $value) {
                $strData.= $key."=".$value."&";

            }
        $strData = substr($strData,0,strlen($strData)-1);
		return $strData;

    }

    
function createHtml($sorted=null, $frontUrl){

// foreach ($sorted as $key => $val) {
//     echo "$key = $val\n";
// }

$html = <<<EOT
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset={$sorted['encoding']}" />
</head>
<body>
	<form id="pay_form" name="pay_form" action="{$frontUrl}" method="post">
EOT;
		foreach ($sorted as $key => $value) {
			$html .= "    <input type=\"hidden\" name=\"{$key}\" id=\"{$key}\" value=\"{$value}\" />\n";
		}
		$html .= <<<EOT
	<input type="submit" type="hidden">
	</form>
</body>
</html>
EOT;
return $html;
}

	public function getKeyStore(){
		$keyStore = null;
		CertUtils::init();
		$success = CertUtils::initCert();
		
		if($success){
			$keystore = CertUtils::getKeystore();
		}
		return $keystore;
	}
	public function getSignature($merged_data=null){
		$keyStore = self::getKeyStore();
		$strData = self::convertToString($merged_data);

		$pkey = $keyStore['pkey'];
		$signedData = CertUtils::generateSignature($pkey, $strData);
		return $signedData;
	}

	public function mergeData($defaultValues, $userData, $type){
		$assVal = self::assignValues($userData);
		$merged_data = array_merge($defaultValues, $assVal,$type);
		return $merged_data;
	}
	public function makeRequest($requestData){
		parent::makeRequest($requestData);
	}
    public function curlPost(stdClass $data, $url, array $headers){
		if(!is_string($url)){
			throw new InvalidArgumentException('URL must be a string');
		}
		else{
			//request datetime
			$request_time = new DateTime();

			//init curl
			$curl = curl_init();

			//build json string
			$data = json_encode($data);

			/**
			 * CURL OPTIONS
			 */
			//set url
			curl_setopt($curl, CURLOPT_URL, $url);

			//set request headers
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

			//return transfer response as string to the $curl resource
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

			//follow any 'Location:' header the server sends
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

			//output verbose info
			curl_setopt($curl, CURLOPT_VERBOSE, true);

			//request method is POST
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");

			//request body
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

			$output = curl_exec($curl);
		}
	}

}
?>