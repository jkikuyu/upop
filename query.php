<?php

//inquiry interface example

require_once('../quickpay_service.php');

//* data for test
$transType   = quickpay_conf::CONSUME;
$orderNumber = "20111108150703852";
$orderTime   = "20111108150703";
// */

//need to fill in
$param['transType']     = $transType;   //transaction type
$param['orderNumber']   = $orderNumber; //order number
$param['orderTime']     = $orderTime;   //transaction time

//submit inquiry
$query  = new quickpay_service($param, quickpay_conf::QUERY);
$ret    = $query->post();

//return inquery result
$response = new quickpay_service($ret, quickpay_conf::RESPONSE);

//Subsequent processing
$arr_ret = $response->get_args();
echo "The return of inquiry: <pre>\n" .  var_export($arr_ret, true) . "</pre>";

$respCode = $response->get('respCode');
$queryResult = $response->get('queryResult');

if ($queryResult == quickpay_service::QUERY_FAIL) 
{
    echo "Transaction failure[respCode:{$respCode}]!\n";
    //update database and set to transaction failure
}
else if ($queryResult == quickpay_service::QUERY_INVALID) {
    //error
    echo "No such transaction!\n";
}
else if ($respCode == quickpay_service::RESP_SUCCESS
        && $queryResult == quickpay_service::QUERY_SUCCESS)
{
    echo "Transaction success!\n";
    //update database and set to transaction success
}
else if ($respCode == quickpay_service::RESP_SUCCESS
        && $queryResult == quickpay_service::QUERY_WAIT)
{
    echo "Transaction processing, please do inquiry next time!\n";
}
else 
{
    //Other errors
    $err = sprintf("Error[respCode:%d]", $response->get('respCode'));
    throw new Exception($err);
}


?>
