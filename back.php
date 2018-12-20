<?php

//backend interface example

require_once('../quickpay_service.php');

//The following line is used for test to generate a random and unique order number
mt_srand(quickpay_service::make_seed());

//transaction type. refund: REFUND or purchase cancellation: CONSUME_VOID, and if the original transaction is PRE_AUTH, the backend interface also supports corresponding
//  PRE_AUTH_VOID(pre-authorization cancellation), PRE_AUTH_COMPLETE(pre-authorization completion), PRE_AUTH_VOID_COMPLETE(pre-authorization completion cancellation)
$param['transType']             = quickpay_conf::CONSUME_VOID;  

$param['origQid']               = '201110281442120195882'; //qid returned by original transaction, get from database

$param['orderAmount']           = 11000;        //Amount of the transaction
$param['orderNumber']           = date('YmdHis') . strval(mt_rand(100, 999)); //order number, must be unique(should not be same as the original transaction)
$param['orderTime']             = date('YmdHis');   //transaction time, YYYYmmhhddHHMMSS
$param['orderCurrency']         = quickpay_conf::CURRENCY_CNY;  //transaction currency

$param['customerIp']            = $_SERVER['REMOTE_ADDR'];  //User IP
$param['frontEndUrl']           = "";    //Frontend callback URL, could be null when backend transaction
$param['backEndUrl']            = "http://www.example.com/sdk/utf8/back_notify.php";    //Backend callback URL

//Other parameters could be null

//submit
$pay_service = new quickpay_service($param, quickpay_conf::BACK_PAY);
$ret = $pay_service->post();

//Synchronous return (it means the server has received the request of the background interface), whether process is successful depends on backend notification, or active inquiry
$response = new quickpay_service($ret, quickpay_conf::RESPONSE);
if ($response->get('respCode') != quickpay_service::RESP_SUCCESS) { //error handling
    $err = sprintf("Error: %d => %s", $response->get('respCode'), $response->get('respMsg'));
    throw new Exception($err);
}

//Subsequent processing
$arr_ret = $response->get_args();

echo "Backend transaction return:\n" . var_export($arr_ret, true); //This line is only used to test output

?>
