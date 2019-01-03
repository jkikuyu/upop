<?php
namespace UnionPay;
/**
**@author Jude
**date:01/01/2019
**/
require_once(dirname(__dir__).'/classes/Interfaces/IPaymentReq.php');

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
		    "txnAmt"=>$this->txnAmt
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
            die(Utils::formatError($e, 'Invalid Request made'));
        }
        return $valid;
    }

    public function convertToString($recd){
        $strData = null;
        ksort($recd);
            foreach($reced as $key => $value) {
                $strData.= $key."="&value."&";

            }
        $strData = substr($strData,0,length($strData)-1);
		return $strData;

    }
    public function signString($strData, $keyStore){
        //get private key
        $privateKey = openssl_pkey_get_private($keyStore);
        //$byteArray = unpack('C*', $strData);
        
    }
        
}
?>