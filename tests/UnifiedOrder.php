<?php
/**
 * The following code, none of which has BUG.
 *
 * @author: BD<liuxingwu@duoguan.com>
 * @date: 2019/6/22 15:45
 */
use xin\payment\PaymentException;
use xin\payment\PaymentFactory;
use xin\payment\PayType;
use xin\payment\TradeType;
use xin\payment\UnifiedOrderOptions;

require_once '../vendor/autoload.php';

$payment = PaymentFactory::factoryBasic([
	'wechat' => [
		'appid'    => '',
		'mch_id'   => '',
		'sign_key' => '',
	],
]);
$input = new UnifiedOrderOptions();
$input->setOutTradeNo(time());
$input->setBody('测试支付');
$input->setTotalFee(100);
$input->setPayType(PayType::WECHAT);
$input->setTradeType(TradeType::JSAPI);
$input->setNotifyUrl('https://www.baidu.com');
$input->setOpenid('1234567890');
try{
	$result = $payment->unifiedOrder($input);
	var_dump($result);
}catch(PaymentException $e){
	var_dump("error:".$e->getMessage());
}
