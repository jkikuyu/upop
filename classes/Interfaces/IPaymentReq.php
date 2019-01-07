<?php
namespace UnionPay;
interface IPaymentReq{
	public function assignValues();
    public function isRequestValid();
	public function convertToString();
	public function getKeyStore();
	public function generateSignature();
	public function makeRequest();
}
?>