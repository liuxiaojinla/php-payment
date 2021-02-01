<?php
use Xin\Payment\Bus\Base\RefundQueryInput;
use Xin\Payment\Bus\PayChannelEnum;

require_once '../vendor/autoload.php';
require_once './init.php';

$input = new RefundQueryInput();
$input->setOutTradeNo('1612173980');
$input->setChannel(PayChannelEnum::WECHAT);
$result = payment()->refundQuery($input);
var_dump($result->toArray());
