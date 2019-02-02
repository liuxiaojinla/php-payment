<?php
/**
 * I know no such things as genius,it is nothing but labor and diligence.
 *
 * @copyright (c) 2015~2019 BD All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author BD<657306123@qq.com>
 */

namespace xin\payment;

use xin\payment\data\CloseOrderPaymentOptions;
use xin\payment\data\CloseOrderPaymentResult;
use xin\payment\data\OrderQueryPaymentOptions;
use xin\payment\data\OrderQueryPaymentResult;
use xin\payment\data\PaymentOptions;
use xin\payment\data\PaymentResult;
use xin\payment\data\RefundPaymentOptions;
use xin\payment\data\RefundPaymentResult;
use xin\payment\data\RefundQueryPaymentOptions;
use xin\payment\data\RefundQueryPaymentResult;
use xin\payment\data\ReversePaymentOptions;
use xin\payment\data\ReversePaymentResult;
use xin\payment\data\UnifiedOrderPaymentOptions;
use xin\payment\data\UnifiedOrderPaymentResult;

/**
 * Interface PaymentInterface
 *
 * @package xin\payment
 */
interface PaymentInterface{

	/**
	 * 统一下单
	 *
	 * @param UnifiedOrderPaymentOptions $input
	 * @return UnifiedOrderPaymentResult
	 * @throws PaymentException
	 */
	public function unifiedOrder(UnifiedOrderPaymentOptions $input);

	/**
	 * 查询订单
	 *
	 * @param OrderQueryPaymentOptions $input
	 * @return OrderQueryPaymentResult
	 * @throws PaymentException
	 */
	public function orderQuery(OrderQueryPaymentOptions $input);

	/**
	 * 关闭订单
	 *
	 * @param CloseOrderPaymentOptions $input
	 * @return CloseOrderPaymentResult
	 * @throws PaymentException
	 */
	public function closeOrder(CloseOrderPaymentOptions $input);

	/**
	 * 申请退款
	 *
	 * @param RefundPaymentOptions $input
	 * @return RefundPaymentResult
	 * @throws PaymentException
	 */
	public function refund(RefundPaymentOptions $input);

	/**
	 * 退款查询
	 *
	 * @param RefundQueryPaymentOptions $input
	 * @return RefundQueryPaymentResult
	 * @throws PaymentException
	 */
	public function refundQuery(RefundQueryPaymentOptions $input);

	/**
	 * 撤销订单
	 *
	 * @param ReversePaymentOptions $input
	 * @return ReversePaymentResult
	 * @throws PaymentException
	 */
	public function reverse(ReversePaymentOptions $input);

	/**
	 * 设置支付签名
	 * @param PaymentOptions $options
	 * @throws PaymentException
	 */
	public function setSign(PaymentOptions $options);

	/**
	 * 验证签名
	 * @param PaymentResult $result
	 * @throws PaymentException
	 */
	public function checkSign(PaymentResult $result);
}
