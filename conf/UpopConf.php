<?php
namespace UnionPay;

use Dotenv\Dotenv;   

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
	/** backUrl  */
	private $backUrl;
	/** frontUrl  */
	private $frontUrl;
	/** Background request URL. */
	private $backRequestUrl;

    private $certId;

    public $frontTransUrl;

	private $orderId;
	private $txnTime;
	private $txnAmt;
	private $merId;
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
        $this->merId=getenv('UPOP.MERCHANTID');
        $this->currencyCode=getenv('UPOP.CURRENCYCODE');
        //$this->payTimeOut=getenv('UPOP.PAYTIMEOUT');
        $this->smsCode=getenv('UPOP.SMSCODE');
		$this->backUrl = getenv('UPOP.BACKURL');
        $this->backTransUrl=getenv('UPOP.BACKTRANSURL');
        $this->frontTransUrl=getenv('UPOP.FRONTTRANSURL');
		$this->certId = getenv('UPOP.CERTID');
		$this->port = getenv('UPOP.PORT');

    }
    
    public function getDefaultContent(){
        $content = array(
            "version"=>$this->version,
            "encoding"=>$this->encoding,
            "signMethod" =>$this->signMethod, 
            "bizType"=>$this->bizType,
            "accessType"=>$this->accessType,
            "channelType"=>$this->channelType,
            "merId"=>$this->merId,
            "currencyCode"=>$this->currencyCode,
            "certId" => $this->certId,
			"backUrl"=> $this->backUrl
        );
    
        return $content;
    }

    public function getRequiredFlds(){
        $required_data = [
            'version',
            'encoding',
            'signMethod',
            'txnType',
            'txnSubType',
            'bizType',
            'accessType',
            'channelType',
            'merId',
            'orderId',
            'txnTime',
            'currencyCode',
            'txnAmt',
            'certId',
			'backUrl'
        ];
        return $required_data;
    }
    public function getRequiredUserInputs(){
        $requiredInputs=['orderId','txnAmt','txnTime','type'];
		return $requiredInputs;
    }
}

?>
