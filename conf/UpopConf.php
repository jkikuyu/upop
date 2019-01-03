<?php
namespace UnionPay;
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
$dotenv = new Dotenv\Dotenv(__DIR__);

$dotenv->load();

/** For version, use the version 5.0.0 if this parameter has not been set.  */
private $version;
/** For signMethod, use the method corresponding to 01 if this parameter has not been set.  */
private encoding;
private $signMethod;
private txntype;
private txnSubType;
private bizType;
private accessType;
private channelType;
private currencyCode;
private paytTimeOut;
    
/** frontUrl  */
private $frontUrl;

/** backUrl  */
private $backUrl;
private orderID;
private txnTime;
private txnAmt;
private $mechantID;

class UpopConf{
    
    public function __construct($dataInput){
        $this->version=getenv('UPOP.VERSION');
        $this->encoding=getenv('UPOP.ENCODING');
        $this->signMethod=getenv('UPOP.SIGNMETHOD');
        $this->txntype=getenv('UPOP.TXNTYPE');
        $this->txnSubType=getenv('UPOP.TXNSUBTYPE');
        $this->bizType=getenv('UPOP.BIZTYPE');
        $this->accessType=getenv('UPOP.ACCESSTYPE');
        $this->channelType=getenv('UPOP.CHANNELTYPE');
        $this->merchantID=getenv('UPOP.MERCHANTID ');
        $this->currencyCode=getenv('UPOP.CURRENCYCODE');
        $this->paytTimeOut=getenv('UPOP.PAYTIMEOUT');
        $this->smsCode=getenv('UPOP.SMSCODE');
        $this->frontUrl=getenv('UPOP.BACKURL');
        $this->backUrl=getenv('UPOP.FRONTURL');
        $this->orderID=$dataInput->orderID;
        $this->txnTime=$dataInput->txnTime;
        $this->txnAmt =$dataInput->txnAmt;
        


    }
    
    public setContentData(){
        contentData = new array(
            "version"=>$this->version,
            "encoding"=>$this->encoding,
             "signMethod" =>$this->signMethod, 
            "txnType"=>$this->txntype,
            "txnSubType"=>$this->txnSubType,
            "bizType"=>$this->bizType,
            "accessType"=>$this->accessType,
            "channelType"=>$this->channelType,
            "mechantID"=>$this->merchantID,
            "orderID"=$this->orderID,
            "txnTime"=$this->txnTime,
            "currencyCode"=>$this->currencyCode,
            "payTimeout"=> $this->paytimeout,
            "backurl"=>$this->backURL,
            "fronturl"=>$this->frontURL,
            "txnAmt"=>$this->txnAmt
        );
    
        return $contentData;
    }
    
    public requiredData(){
        $required_data = [
            'version',
            'encoding',
            'signMethod',
            'txnType',
            'txnSubType',
            'bizType',
            'email'
        ];
    }
    public requiredConf(){
        
    }
}

?>
