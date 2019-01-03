<?php
namespace UnionPay;
use Dotenv\Dotenv;   
use UnionPay\UpopConf;

require_once(dirname(__dir__).'/Interfaces/IPaymentType.php');
require_once(dirname(__dir__).'/PaymentReq.php');


class Purchase extends PaymentReq implements IPaymentType{
        public function __construct(){

            $this->txntype=getenv('UPOP.TXNTYPE');
            $this->txnSubType=getenv('UPOP.TXNSUBTYPE');
        }
        
        public function processRequest($userData){
			/*
			* i. create array using received data
			* ii. get mandatory data
			* iii. Array merge 
			* iv. validate merged array
			*/
			parent::assignValues($userData);
			
            $mandatory = new UpopConf.getMandatoryData();
			$mergedData = array_merge($mandatory, $mergedData);
			$requiredData = new UpopConf.getRequiredData();
			$oData = $mergedData;
			var_dump($oData);
			parent::isRequestValid($oData,$requiredData);
			
			
        }
        public function init(){
            
        }
		public function test(){
			echo "test";
		}
}

?>