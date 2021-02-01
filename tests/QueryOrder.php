<?php
use Xin\Payment\Bus\Base\OrderQueryInput;
use Xin\Payment\Bus\PayChannelEnum;

require_once '../vendor/autoload.php';
require_once './init.php';

$input = new OrderQueryInput();
$input->setOutTradeNo(1612173980);
$input->setChannel(PayChannelEnum::WECHAT);
$result = payment()->orderQuery($input);

var_dump("appid:".$result->getAppid());
var_dump("mchid:".$result->getMchId());
var_dump("total_fee:".$result->getTotalFee());
var_dump("out_trade_no:".$result->getOutTradeNo());
var_dump("trade_state:".$result->getTradeState());
var_dump("trade_state_desc:".$result->getTradeStateDesc());
var_dump($result->toArray());
