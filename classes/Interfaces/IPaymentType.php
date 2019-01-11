<?php
namespace UnionPay;
interface IPaymentType{
    //public function init();
     public function processRequest($userData);
}
?>