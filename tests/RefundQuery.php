<?php
/**
 * The following code, none of which has BUG.
 *
 * @author: BD<liuxingwu@duoguan.com>
 * @date: 2019/6/22 15:45
 */
use xin\payment\PaymentException;
use xin\payment\PayType;
use xin\payment\RefundOptions;
use xin\payment\RefundQueryOptions;
use xin\payment\TradeType;
use xin\payment\UnifiedOrderOptions;

require_once '../vendor/autoload.php';
require_once './init.php';

$orderSn = time();
var_dump($orderSn);

$input = new RefundQueryOptions();
$input->setOutTradeNo('1561279171');
$input->setPayType(PayType::WECHAT);

try{
	$payment = get_payment();
	$result = $payment->refundQuery($input);
	var_dump($result);
}catch(PaymentException $e){
	var_dump("error:".$e->getMessage());
}
