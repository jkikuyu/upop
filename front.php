<?php

//frontend interface example

require_once(dirname(__FILE__).'/conf/quickpay_service.php');

//The following line is used for test to generate a random and unique order number
mt_srand(quickpay_service::make_seed());

$param['transType']             = quickpay_conf::CONSUME;  //transaction type, CONSUME or PRE_AUTH

$param['orderAmount']           = 11000;        //Amount of the transaction
$param['orderNumber']           = date('YmdHis') . strval(mt_rand(100, 999)); //order number, must be unique
$param['orderTime']             = date('YmdHis');   //transaction time, YYYYmmhhddHHMMSS
$param['orderCurrency']         = quickpay_conf::CURRENCY_CNY;  //transaction currency, CURRENCY_CNY=>Chinese Yuan

$param['customerIp']            = $_SERVER['REMOTE_ADDR'];  //User IP
$param['frontEndUrl']           = "https://41.212.60.154/upop/front_notify.php";    //Frontend callback URL
$param['backEndUrl']            = "https://41.212.60.154/upop/back_notify.php";    //Backend callback URL

/* not necessary fields
   $param['commodityUrl']          = "http://www.example.com/product?name=product";  //product URL
   $param['commodityName']         = 'product name';   //product name
   $param['commodityUnitPrice']    = 11000;        //Product unit price
   $param['commodityQuantity']     = 1;            //Product quantity
//*/

//Other parameters could be null

$pay_service = new quickpay_service($param, quickpay_conf::FRONT_PAY);
$html = $pay_service->create_html();

header("Content-Type: text/html; charset=" . quickpay_conf::$pay_params['charset']);
echo $html; //automatically post form

?>
