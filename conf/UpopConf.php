<?php
namespace UnionPay;

use Dotenv\Dotenv;   

/*
$dotenv = new Dotenv\Dotenv(__DIR__);

$dotenv->load();
*/

/** For version, use the version 5.0.0 if this parameter has not been set.  */




class UpopConf{
	private $version;
	private $encoding;
	private $signMethod;
	private $txntype;
	private $txnSubType;
	private $bizType;
	private $accessType;
	private $channelType;
	private $currencyCode;
	private $paytTimeOut;
	/** backUrl  */
	private $backUrl;
	/** frontUrl  */
	private $frontUrl;

	private $orderID;
	private $txnTime;
	private $txnAmt;
	private $mechantID;
	const PURCHASE = 1;
	const CANCELPURCHASE  = 2;
	const REFUND  = 3;
	const PREAUTH = 4;
	const CANCELPREAUTH=5;
	const COMPLETEPREAUTH=6;
	const CANCELCOMPLETEPREAUTH=7;
	const RECURRING=8;

    
    public function __construct(){
		$this->version=getenv('UPOP.VERSION');
        $this->encoding=getenv('UPOP.ENCODING');
        $this->signMethod=getenv('UPOP.SIGNMETHOD');
        $this->bizType=getenv('UPOP.BIZTYPE');
        $this->accessType=getenv('UPOP.ACCESSTYPE');
        $this->channelType=getenv('UPOP.CHANNELTYPE');
        $this->merchantID=getenv('UPOP.MERCHANTID ');
        $this->currencyCode=getenv('UPOP.CURRENCYCODE');
        $this->paytTimeOut=getenv('UPOP.PAYTIMEOUT');
        $this->smsCode=getenv('UPOP.SMSCODE');
        $this->frontUrl=getenv('UPOP.BACKURL');
        $this->backUrl=getenv('UPOP.FRONTURL');


    }
    
    public function getMandatoryData(){
        $contentData = array(
            "version"=>$this->version,
            "encoding"=>$this->encoding,
             "signMethod" =>$this->signMethod, 
            "bizType"=>$this->bizType,
            "accessType"=>$this->accessType,
            "channelType"=>$this->channelType,
            "mechantID"=>$this->merchantID,
            "currencyCode"=>$this->currencyCode,
            "payTimeout"=> $this->paytimeout,
            "backurl"=>$this->backURL,
            "fronturl"=>$this->frontURL
        );
    
        return $contentData;
    }
    
    public function getRequiredData(){
        $required_data = [
            'version',
            'encoding',
            'signMethod',
            'txnType',
            'txnSubType',
            'bizType',
            'accessType',
            'channelType',
            'mechantID',
            'orderID',
            'txnTime',
            'currencyCode',
            'payTimeout',
            'backurl',
            'fronturl',
            'txnAmt',
            'type'
        ];
        return $required_data;
    }
    public function getRequiredUserData(){
        $required_data=['orderID','txnAmt','txnTime','type'];
		return $required_data;
    }
}

?>
