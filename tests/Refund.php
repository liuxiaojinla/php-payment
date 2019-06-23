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
use xin\payment\TradeType;
use xin\payment\UnifiedOrderOptions;

require_once '../vendor/autoload.php';
require_once './init.php';

$orderSn = time();
var_dump($orderSn);

$input = new RefundOptions();
$input->setOutTradeNo('1561279171');
$input->setOutRefundNo($orderSn);
$input->setTotalFee(100);
$input->setRefundFee(100);
$input->setOpUserId(100);
$input->setPayType(PayType::WECHAT);

try{
	$payment = get_payment();
	$result = $payment->refund($input);
	var_dump($result);
}catch(PaymentException $e){
	var_dump("error:".$e->getMessage());
}
