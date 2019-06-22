<?php
/**
 * I know no such things as genius,it is nothing but labor and diligence.
 *
 * @copyright (c) 2015~2019 BD All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author BD<657306123@qq.com>
 */

namespace xin\payment;

/**
 * Interface PaymentInterface
 *
 * @package xin\payment
 */
interface PaymentInterface{

	/**
	 * 统一下单
	 *
	 * @param UnifiedOrderOptions $input
	 * @return UnifiedOrderResult
	 * @throws PaymentException
	 */
	public function unifiedOrder(UnifiedOrderOptions $input);

	/**
	 * 查询订单
	 *
	 * @param OrderQueryOptions $input
	 * @return OrderQueryResult
	 * @throws PaymentException
	 */
	public function orderQuery(OrderQueryOptions $input);

	/**
	 * 关闭订单
	 *
	 * @param CloseOrderOptions $input
	 * @return CloseOrderResult
	 * @throws PaymentException
	 */
	public function closeOrder(CloseOrderOptions $input);

	/**
	 * 申请退款
	 *
	 * @param RefundOptions $input
	 * @return RefundResult
	 * @throws PaymentException
	 */
	public function refund(RefundOptions $input);

	/**
	 * 退款查询
	 *
	 * @param RefundQueryOptions $input
	 * @return RefundQueryResult
	 * @throws PaymentException
	 */
	public function refundQuery(RefundQueryOptions $input);

	/**
	 * 撤销订单
	 *
	 * @param ReverseOptions $input
	 * @return ReverseResult
	 * @throws PaymentException
	 */
	public function reverse(ReverseOptions $input);
	//	/**
	//	 * 设置支付签名
	//	 *
	//	 * @param PaymentOptions $input
	//	 * @throws PaymentException
	//	 */
	//	public function setSign(PaymentOptions $input);

	/**
	 * 验证签名
	 *
	 * @param PaymentResult $result
	 * @throws PaymentException
	 */
	public function checkSign(PaymentResult $result);
}
