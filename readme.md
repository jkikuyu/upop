UNION PAY API

2019-01-03

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

