<?php
/**
 * Talents come from diligence, and knowledge is gained by accumulation.
 *
 * @author: 晋<657306123@qq.com>
 */
use Xin\Payment\PayChannel;

require_once '../vendor/autoload.php';
$payment = require_once './init.php';

$notify = payment()->notify(PayChannel::WECHAT);
if($notify->isOk()){
	echo $notify->replySuccess();
}

echo $notify->replyError('支付失败');
