<?php
use Xin\Payment\Bus\Base\RefundInput;
use Xin\Payment\Bus\PayChannelEnum;

require_once '../vendor/autoload.php';
require_once './init.php';

$orderSn = time();
var_dump($orderSn);

$input = new RefundInput();
$input->setOutTradeNo('1612173980');
$input->setOutRefundNo($orderSn);
$input->setTotalFee(100);
$input->setRefundFee(100);
$input->setOpUserId(100);
$input->setChannel(PayChannelEnum::WECHAT);

$result = payment()->refund($input);

var_dump("appid:".$result->getAppid());
var_dump("mchid:".$result->getMchId());
var_dump("total_fee:".$result->getTotalFee());
var_dump("out_trade_no:".$result->getOutTradeNo());
var_dump($result->toArray());
