<?php
use Xin\Payment\Bus\Base\UnifiedOrderInput;
use Xin\Payment\Bus\PayChannel;
use Xin\Payment\Bus\TradeType;

require_once '../vendor/autoload.php';
$payment = require_once './init.php';

$orderSn = time();
var_dump($orderSn);

//Pay::wechat()->miniapp();

$input = new UnifiedOrderInput();
$input->setOutTradeNo($orderSn);
$input->setBody('测试支付');
$input->setTotalFee(100);
$input->setNotifyUrl('https://www.baidu.com');
$input->setOpenid('o49390NOh_fmsdpZCEgoWbC_8nws');

$input->setChannel(PayChannel::WECHAT);
$input->setChannel(PayChannel::ALIPAY);

$input->setTradeType(TradeType::JSAPI);
$input->setTradeType(TradeType::NATIVE);
$input->setTradeType(TradeType::MINI_APP);
//$input->setTradeType(TradeType::APP);
//$input->setTradeType(TradeType::WAP);

//$input->setTradeType(TradeType::SCAN);
//$input->setAuthCode('1354804793001231564897');

$result = payment()->unifiedOrder($input);

if($result->getTradeType() === TradeType::JSAPI || TradeType::MINI_APP === $result->getTradeType()){
	var_dump("appid:".$result->appId);
	var_dump($result->toArray());
}else{
	var_dump("appid:".$result->getAppid());
	var_dump("mchid:".$result->getMchId());
	var_dump("nonce_str:".$result->getNonceStr());
	var_dump("prepay_id:".$result->getPrepayId());
	var_dump("code_url:".$result->getCodeUrl());
}
