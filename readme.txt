PHP SDK rev 1.0.1 

2011-09-05

==== Requirement ====

1. PHP version 5.x 

2. PHP mbstring or iconv mode

3. curl mode is manditory for backend transaction and inquiry transaction.

Ubuntu:
    sudo apt-get install php5_curl php5_mbstring

Windows: modify php.ini, enable the following extensions by remove ";"
    ;extension=php_curl.dll 
    ;extension=php_mbstring.dll 

After modification please restart web server (apache/nginx/ligttpd/iis)

Notes: You could check above extensions via <?php phpinfo(); ?>


==== Instructions ====

0. Please read "UnionPay Online Payment Interface Specification" and "UPOP Program Implementation Guide" carefully.

1. Select GBK or UTF-8 encoding SDK version according to your own codes.
   Please transfer other encodings by yourself, and modify character encoding in quickpay_conf.php

2. Modify parameters in quickpay_conf.php, which includes:

    security_key (Merchants and acquirers all are required to complete)

    merId (for merchants)
    acqCode (for acquirer, merchants please keep it null)
    merAbbr (for merchants, acquirers could specify this by request parameters)

    You need to change api URL according to test environment, pre-online environment, online environment

3. Please refer to front.php/back.php/query.php to complete frontend transaction(purchase, pre-authorization), backend interface call(purchase cancellation, refund, pre-authorization Subsequent process), transaction inquiry. Please refer to front_notify.php/back_notify.php to complete frontend/backend notification process. If and only if user payment succeeds, our server will start backend notification (There is no DNS service in test environment. Please use ip instead of domain name to test in backEndUrl) to update transaction state. Payment success page will display "Return to merchant" button, when the user click it or 30 seconds later, the page will redirect to the URL which specified by the parameter frontEndUrl, to let the user know the transaction success.

4. Please pass the test with default merchant ID and security key in test environment, then contact us to config online merchant ID and key in pre-online environment. After pass the test in pre-online environment, switch to online environment.
    The available card information in test environment:
        Card Number: 6212341111111111111
        Password: any 6 digits
        SMS verification code: any 6 digits

5. If you have problems, please specify and describe the problem with request parameters when contact us.

