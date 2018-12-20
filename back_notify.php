<?php

require_once('../quickpay_service.php');

try {
    $response = new quickpay_service($_POST, quickpay_conf::RESPONSE);
    if ($response->get('respCode') != quickpay_service::RESP_SUCCESS) {
        $err = sprintf("Error: %d => %s", $response->get('respCode'), $response->get('respMsg'));
        throw new Exception($err);
    }

    $arr_ret = $response->get_args();

    //Update database, change the transaction state to payed
    //Attention to save qid to call backend interface to refund or purchase cancellation

    //Just for test
    file_put_contents('notify.txt', var_export($arr_ret, true));

}
catch(Exception $exp) {
    //backend notification error
    file_put_contents('notify.txt', var_export($exp, true));
}

?>
