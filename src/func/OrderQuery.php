<?php
/**
 * The following code, none of which has BUG.
 *
 * @author: BD<657306123@qq.com>
 * @date: 2019/11/26 14:29
 */

namespace xin\payment\func;

use xin\payment\ConfigInterface;
use xin\payment\entity\PaymentOptions;
use xin\payment\PaymentException;
use xin\payment\TradeType;

class OrderQuery extends BaseFunc{

	/**
	 * 支付宝
	 *
	 * @param \xin\payment\ConfigInterface       $config
	 * @param \xin\payment\entity\PaymentOptions $options
	 * @return mixed
	 */
	protected function onAliPay(ConfigInterface $config, PaymentOptions $options){
		// TODO: Implement onAlipay() method.
	}

	/**
	 * 微信
	 *
	 * @param \xin\payment\ConfigInterface       $config
	 * @param \xin\payment\entity\PaymentOptions $input
	 * @return mixed
	 * @throws \xin\payment\PaymentException
	 */
	protected function onWxPay(ConfigInterface $config, PaymentOptions $input){

	}
}
