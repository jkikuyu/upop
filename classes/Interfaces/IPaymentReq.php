<?php
namespace UnionPay;
interface IPaymentReq{
	public function assignValues($dataRecd);
    public function isRequestValid();
	public function convertToString();
	public function getKeyStore();
	public function  createHtml($sorted, $frontUrl);
	public function  getSignature();
	public function mergeData($defaultValues, $userData, $type);
	//public function generateSignature($pkey, $strData);
	public function makeRequest($requestData);
}
?>