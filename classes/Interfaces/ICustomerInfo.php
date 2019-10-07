<?php
namespace UnionPay;
interface ICustomerInfo{


	public function encryptCard($card);
	public function encryptedCertId();
}
?>