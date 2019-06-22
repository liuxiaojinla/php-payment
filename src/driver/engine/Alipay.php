<?php
/**
 * The following code, none of which has BUG.
 *
 * @author: BD<liuxingwu@duoguan.com>
 * @date: 2019/6/22 14:55
 */

namespace xin\payment\driver\engine;

use xin\payment\CloseOrderOptions;
use xin\payment\CloseOrderResult;
use xin\payment\OrderQueryOptions;
use xin\payment\OrderQueryResult;
use xin\payment\Payment;
use xin\payment\PaymentException;
use xin\payment\PaymentResult;
use xin\payment\RefundOptions;
use xin\payment\RefundQueryOptions;
use xin\payment\RefundQueryResult;
use xin\payment\RefundResult;
use xin\payment\ReverseOptions;
use xin\payment\ReverseResult;
use xin\payment\UnifiedOrderOptions;
use xin\payment\UnifiedOrderResult;

class Alipay extends Payment{

	/**
	 * 统一下单
	 *
	 * @param UnifiedOrderOptions $input
	 * @return UnifiedOrderResult
	 * @throws PaymentException
	 */
	public function unifiedOrder(UnifiedOrderOptions $input){
		// TODO: Implement unifiedOrder() method.
	}

	/**
	 * 查询订单
	 *
	 * @param OrderQueryOptions $input
	 * @return OrderQueryResult
	 * @throws PaymentException
	 */
	public function orderQuery(OrderQueryOptions $input){
		// TODO: Implement orderQuery() method.
	}

	/**
	 * 关闭订单
	 *
	 * @param CloseOrderOptions $input
	 * @return CloseOrderResult
	 * @throws PaymentException
	 */
	public function closeOrder(CloseOrderOptions $input){
		// TODO: Implement closeOrder() method.
	}

	/**
	 * 申请退款
	 *
	 * @param RefundOptions $input
	 * @return RefundResult
	 * @throws PaymentException
	 */
	public function refund(RefundOptions $input){
		// TODO: Implement refund() method.
	}

	/**
	 * 退款查询
	 *
	 * @param RefundQueryOptions $input
	 * @return RefundQueryResult
	 * @throws PaymentException
	 */
	public function refundQuery(RefundQueryOptions $input){
		// TODO: Implement refundQuery() method.
	}

	/**
	 * 撤销订单
	 *
	 * @param ReverseOptions $input
	 * @return ReverseResult
	 * @throws PaymentException
	 */
	public function reverse(ReverseOptions $input){
		// TODO: Implement reverse() method.
	}

	/**
	 * 验证签名
	 *
	 * @param PaymentResult $result
	 * @throws PaymentException
	 */
	public function checkSign(PaymentResult $result){
		// TODO: Implement checkSign() method.
	}
}
