<?php

/*
 * @file    quickpay_service.inc.php
 * @author  fengmin(felix021@gmail.com)
 * @date    2011-08-22
 * @version $Revision$
 *
 */

class quickpay_conf
{

    const VERIFY_HTTPS_CERT = false;

    static $timezone        = "Africa/Nairobi"; //time zone
    static $sign_method     = "md5"; //signature algorithm, only md5 supported now (2011-08-22)

    static $security_key    = "88888888"; //merchant encryption key

    //the pre-defined fields of payment request
    static $pay_params  = array(
        'version'       => '1.0.0',
        'charset'       => 'UTF-8', //UTF-8, GBK
        'merId'         => '000000070000017', //for merchant
        'acqCode'       => '30430404',  //for acquirer
        'merCode'       => '4829',  //for acquirer
        'merAbbr'       => 'IPAY',
    );

    //* Test environment
    static $front_pay_url   = "http://58.246.226.99/UpopWeb/api/Pay.action";
    static $back_pay_url    = "http://58.246.226.99/UpopWeb/api/BSPay.action";
    static $query_url       = "http://58.246.226.99/UpopWeb/api/Query.action";
    //*/

    /* pre-online environment
    static $front_pay_url   = "https://www.epay.lxdns.com/UpopWeb/api/Pay.action";
    static $back_pay_url    = "https://www.epay.lxdns.com/UpopWeb/api/BSPay.action";
    static $query_url       = "https://www.epay.lxdns.com/UpopWeb/api/Query.action";
    //*/

    /* online environment
    static $front_pay_url   = "https://unionpaysecure.com/api/Pay.action";
    static $back_pay_url    = "https://unionpaysecure.com/api/BSPay.action";
    static $query_url       = "https://unionpaysecure.com/api/Query.action";
    //*/
    
    const FRONT_PAY = 1;
    const BACK_PAY  = 2;
    const RESPONSE  = 3;
    const QUERY     = 4;

    const CONSUME                = "01";
    const CONSUME_VOID           = "31";
    const PRE_AUTH               = "02";
    const PRE_AUTH_VOID          = "32";
    const PRE_AUTH_COMPLETE      = "03";
    const PRE_AUTH_VOID_COMPLETE = "33";
    const REFUND                 = "04";
    const REGISTRATION           = "71";

    const CURRENCY_CNY      = "156";

    //payment request fields could be null (but must complete)
    static $pay_params_empty = array(
        "origQid"           => "",
        "acqCode"           => "",
        "merCode"           => "",
        "commodityUrl"      => "",
        "commodityName"     => "",
        "commodityUnitPrice"=> "",
        "commodityQuantity" => "",
        "commodityDiscount" => "",
        "transferFee"       => "",
        "customerName"      => "",
        "defaultPayType"    => "",
        "defaultBankNumber" => "",
        "transTimeout"      => "",
        "merReserved"       => "",
    );

    //required fields check of payment request
    static $pay_params_check = array(
        "version",
        "charset",
        "transType",
        "origQid",
        "merId",
        "merAbbr",
        "acqCode",
        "merCode",
        "commodityUrl",
        "commodityName",
        "commodityUnitPrice",
        "commodityQuantity",
        "commodityDiscount",
        "transferFee",
        "orderNumber",
        "orderAmount",
        "orderCurrency",
        "orderTime",
        "customerIp",
        "customerName",
        "defaultPayType",
        "defaultBankNumber",
        "transTimeout",
        "frontEndUrl",
        "backEndUrl",
        "merReserved",
    );

    //required fields check of inquiry request
    static $query_params_check = array(
        "version",
        "charset",
        "transType",
        "merId",
        "orderNumber",
        "orderTime",
        "merReserved",
    );

    //possible merchant reserved fields
    static $mer_params_reserved = array(
    //  NEW NAME            OLD NAME
        "cardNumber",       "pan",
        "cardPasswd",       "password",
        "credentialType",   "idType",
        "cardCvn2",         "cvn",
        "cardExpire",       "expire",
        "credentialNumber", "idNo",
        "credentialName",   "name",
        "phoneNumber",      "mobile",
        "merAbstract",

        //tdb only
        "orderTimeoutDate",
        "origOrderNumber",
        "origOrderTime",
    );

    static $notify_param_check = array(
        "version",
        "charset",
        "transType",
        "respCode",
        "respMsg",
        "respTime",
        "merId",
        "merAbbr",
        "orderNumber",
        "traceNumber",
        "traceTime",
        "qid",
        "orderAmount",
        "orderCurrency",
        "settleAmount",
        "settleCurrency",
        "settleDate",
        "exchangeRate",
        "exchangeDate",
        "cupReserved",
        "signMethod",
        "signature",
    );

    static $sign_ignore_params = array(
        "bank",
    );
}

?>
