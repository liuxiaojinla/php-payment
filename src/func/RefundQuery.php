<?php
/**
 * The following code, none of which has BUG.
 *
 * @author: BD<657306123@qq.com>
 * @date: 2019/11/26 16:57
 */

namespace xin\payment\func;

use xin\payment\ConfigInterface;
use xin\payment\entity\PaymentInput;
use xin\payment\entity\RefundQueryOutput;
use xin\payment\PaymentException;

/**
 * Class RefundQuery 查询退款
 * 提交退款申请后，通过调用该接口查询退款状态。退款有一定延时，
 * 用零钱支付的退款20分钟内到账，银行卡支付的退款3个工作日后重新查询退款状态。
 * WxPayRefundQuery中out_refund_no、out_trade_no、transaction_id、refund_id四个参数必填一个
 * appid、mchid、spbill_create_ip、nonce_str不需要填入
 *
 * @package xin\payment\func
 */
class RefundQuery extends BaseFunc{

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
	 * @throws \xin\payment\PaymentException
	 */
	protected function onWxPay(ConfigInterface $config, PaymentInput $input){
		if(!$input->hasOutRefundNo()
			&& !$input->hasOutTradeNo()
			&& !$input->hasTransactionId()
			&& !$input->hasRefundId()){
			throw new PaymentException("退款查询接口中，out_refund_no、out_trade_no、transaction_id、refund_id四个参数必填一个！");
		}

		$url = "https://api.mch.weixin.qq.com/pay/refundquery";
		$response = WxPayUtil::request($url, $input->toXml());
		$result = WxPayUtil::makeOutput($response, RefundQueryOutput::class, $config);

		return $result->transformKeys([]);
	}
}
