<?php
/**
 * The following code, none of which has BUG.
 *
 * @author: BD<657306123@qq.com>
 * @date: 2019/11/26 16:58
 */

namespace xin\payment\func;

use xin\payment\ConfigInterface;
use xin\payment\entity\PaymentOptions;
use xin\payment\PaymentException;

/**
 * Class Reverse 撤销订单API接口
 * out_trade_no和transaction_id必须填写一个
 * appid、mchid、spbill_create_ip、nonce_str不需要填入
 *
 * @package xin\payment\func
 */
class Reverse extends BaseFunc{

	/**
	 * 支付宝
	 *
	 * @param \xin\payment\ConfigInterface       $config
	 * @param \xin\payment\entity\PaymentOptions $input
	 * @return mixed
	 */
	protected function onAliPay(ConfigInterface $config, PaymentOptions $input){
		// TODO: Implement onAliPay() method.
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
		if(!$input->hasOutTradeNo() && !$input->hasTransactionId()){
			throw new PaymentException("撤销订单API接口中，参数out_trade_no和transaction_id必须填写一个！");
		}

		$url = "https://api.mch.weixin.qq.com/secapi/pay/reverse";
		$result = $this->result($url, $input, ReverseResult::class, false, 6);
		return $result->transformKeys([

		]);
	}
}
