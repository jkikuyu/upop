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
        $assVal = array("orderId"=>$dataRecd->orderId,
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

	public function getSignature($merged_data=null){
		$keyStore = CertUtils::getKeyStore();
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
    public function curlPost(array $data, $url,$port){
		$headers = ["Content-type:application/x-www-form-urlencoded;charset=UTF-8"];
		$request_time = new \DateTime();
		$strData = "";
		$output="";

		if(!is_string($url)){
			throw new \InvalidArgumentException('URL must be a string');
		}
		else{
			foreach($data as $key => $value) {
				$strData.= $key."=";

					$strData.= urlencode($value);

					$strData.="&";

			}
			$strData = substr($strData,0,strlen($strData)-1);
			echo "<br />string to send :<br />".$strData."<br/>";
			echo "url :".$url;

			//init curl
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


			/**
			 * CURL OPTIONS
			 */
			//set url
/*
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
			curl_setopt($curl, CURLOPT_POSTFIELDS, $strData);

			$output = curl_exec($curl);
*/
		}
	return $output;

	}
}
?>