<?php
use  Xin\Payment\Bus\RefundQueryInput;
use Xin\Payment\PayChannel;

require_once '../vendor/autoload.php';
require_once './init.php';

$input = new RefundQueryInput();
$input->setOutTradeNo('1612173980');
$input->setChannel(PayChannel::WECHAT);
$result = payment()->refundQuery($input);
var_dump($result->toArray());
