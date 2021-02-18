<?php
/**
 * Talents come from diligence, and knowledge is gained by accumulation.
 *
 * @author: 晋<657306123@qq.com>
 */
use Xin\Payment\PayChannel;

require_once '../vendor/autoload.php';
$payment = require_once './init.php';

$notify = payment()->unifiedOrderNotify(PayChannel::WECHAT);
if($notify->isOk()){
	echo $notify->success();
}

echo $notify->error('支付失败');
