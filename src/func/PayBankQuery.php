<?php
/**
 * The following code, none of which has BUG.
 *
 * @author: BD<657306123@qq.com>
 * @date: 2019/11/27 11:24
 */

namespace xin\payment\func;

use xin\payment\ConfigInterface;
use xin\payment\entity\PaymentInput;

class PayBankQuery extends BaseFunc{

	/**
	 * 支付宝
	 *
	 * @param \xin\payment\ConfigInterface     $config
	 * @param \xin\payment\entity\PaymentInput $input
	 * @return mixed
	 */
	protected function onAliPay(ConfigInterface $config, PaymentInput $input){
		// TODO: Implement onAliPay() method.
	}

	/**
	 * 微信
	 *
	 * @param \xin\payment\ConfigInterface     $config
	 * @param \xin\payment\entity\PaymentInput $input
	 * @return mixed
	 */
	protected function onWxPay(ConfigInterface $config, PaymentInput $input){
		// TODO: Implement onWxPay() method.
	}
}
