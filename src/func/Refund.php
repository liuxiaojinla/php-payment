<?php
/**
 * The following code, none of which has BUG.
 *
 * @author: BD<657306123@qq.com>
 * @date: 2019/11/26 16:56
 */

namespace xin\payment\func;

use xin\payment\ConfigInterface;
use xin\payment\entity\OrderQueryOutput;
use xin\payment\entity\PaymentInput;
use xin\payment\entity\RefundOutput;
use xin\payment\PaymentException;

/**
 * Class Refund 申请退款
 * out_trade_no、transaction_id至少填一个且
 * out_refund_no、total_fee、refund_fee、op_user_id为必填参数
 * appid、mchid、spbill_create_ip、nonce_str不需要填入
 *
 * @package xin\payment\func
 */
class Refund extends BaseFunc{

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
		if(!$input->hasOutTradeNo() && !$input->hasTransactionId()){
			throw new PaymentException("退款申请接口中，out_trade_no、transaction_id至少填一个！");
		}elseif(!$input->hasOutRefundNo()){
			throw new PaymentException("退款申请接口中，缺少必填参数out_refund_no！");
		}elseif(!$input->hasTotalFee()){
			throw new PaymentException("退款申请接口中，缺少必填参数total_fee！");
		}elseif(!$input->hasRefundFee()){
			throw new PaymentException("退款申请接口中，缺少必填参数refund_fee！");
		}elseif(!$input->hasOpUserId()){
			throw new PaymentException("退款申请接口中，缺少必填参数op_user_id！");
		}

		$url = "https://api.mch.weixin.qq.com/secapi/pay/refund";
		$response = WxPayUtil::request($url, $input->toXml());
		$result = WxPayUtil::makeOutput($response, RefundOutput::class, $config);

		return $result->transformKeys([]);
	}
}
