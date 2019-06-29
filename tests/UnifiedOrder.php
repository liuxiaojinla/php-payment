<?php
/**
 * The following code, none of which has BUG.
 *
 * @author: BD<liuxingwu@duoguan.com>
 * @date: 2019/6/22 15:45
 */
use xin\payment\PaymentException;
use xin\payment\PayType;
use xin\payment\TradeType;
use xin\payment\UnifiedOrderOptions;

require_once '../vendor/autoload.php';
require_once './init.php';

$orderSn = time();
var_dump($orderSn);

$input = new UnifiedOrderOptions();
$input->setSubAppid('wx12345678910');
$input->setSubOpenid('o9F2bs2V9FUlaoeggfIo94YRWVS4');
$input->setOutTradeNo($orderSn);
$input->setBody('测试支付');
$input->setTotalFee(100);
$input->setNotifyUrl('https://www.baidu.com');
$input->setOpenid('o9F2bs2V9FUlaoeggfIo94YRWVS4');
$input->setPayType(PayType::WECHAT);
$input->setTradeType(TradeType::JSAPI);

try{
	$payment = get_payment();
	$result = $payment->unifiedOrder($input);
	var_dump($result);
}catch(PaymentException $e){
	var_dump("error:".$e->getMessage());
}
