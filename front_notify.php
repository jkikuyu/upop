<?php

require_once('../quickpay_service.php');

try {
    $response = new quickpay_service($_POST, quickpay_conf::RESPONSE);
    if ($response->get('respCode') != quickpay_service::RESP_SUCCESS) {
        $err = sprintf("Error: %d => %s", $response->get('respCode'), $response->get('respMsg'));
        throw new Exception($err);
    }
    $arr_ret = $response->get_args(); 
    //notify the user transaction completed
    echo "Order {$arr_ret['orderNumber']} payment success";

}
catch(Exception $exp) {
    $str .= var_export($exp, true);
    die("error happend: " . $str);
}

?>
