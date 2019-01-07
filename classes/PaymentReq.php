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
        $assVal = array("orderID"=>$dataRecd->orderID,
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
           	$this->log->error($e . 'Invalid Request made'));
        }
        return $valid;
    }

    public function convertToString($recd=null){
        $strData = null;
        ksort($recd);
            foreach($reced as $key => $value) {
                $strData.= $key."="&value."&";

            }
        $strData = substr($strData,0,length($strData)-1);
		return $strData;

    }
    function createHtml($sorted=null, $frontUrl){
        $html = <<<eot
			<html>
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset={$ $sorted['encoding']}" />
			</head>
			<body>
				<form id="pay_form" name="pay_form" action="{$frontUrl}" method="post">

			eot;
					foreach ($sorted as $key => $value) {
						$html .= "    <input type=\"hidden\" name=\"{$key}\" id=\"{$key}\" value=\"{$value}\" />\n";
					}
					$html .= <<<eot
				<input type="submit" type="hidden">
				</form>
			</body>
			</html>
			eot;
		return $html;
	}

	public function getKeyStore(){
		$keyStore = null;
		CertUtils::init();
		$success = CertUtils::initCert();
		
		if($success){
			$keystore = CertUtils::getKeystore();
		}
		
		return $keyStore;
	}
	public function getSignature($merged_data=null){
		$keyStore = self::getKeyStore();
		$strData = self::convertToString($merged_data);

		$pkey = $keyStore['pkey'];
		$signedData = CertUils::generateSignature($pkey, $strData);
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
    
        
}
?>