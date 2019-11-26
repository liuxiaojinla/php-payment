<?php
/**
 * The following code, none of which has BUG.
 *
 * @author: BD<657306123@qq.com>
 * @date: 2019/11/26 16:54
 */

namespace xin\payment\func;

use xin\payment\ConfigInterface;
use xin\payment\entity\PaymentOptions;
use xin\payment\PaymentException;

/**
 * Class CloseOrder 关闭订单
 * out_trade_no 必填
 * appid、mchid、spbill_create_ip、nonce_str不需要填入
 *
 * @package xin\payment\func
 */
class CloseOrder extends BaseFunc{

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
		if(!$input->hasOutTradeNo()){
			throw new PaymentException("订单查询接口中，out_trade_no必填！");
		}

		$url = "https://api.mch.weixin.qq.com/pay/closeorder";
		$result = $this->result($url, $input, CloseOrderResult::class, false, 6);
		return $result->transformKeys([

		]);
	}
}
