<?php
/**
 * The following code, none of which has BUG.
 *
 * @author: BD<liuxingwu@duoguan.com>
 * @date: 2019/6/22 14:54
 */

namespace xin\payment\driver;

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

class Basic extends Payment{

	/**
	 * Basic constructor.
	 *
	 * @param array $config
	 */
	public function __construct(array $config){
		parent::__construct($config);
	}

	/**
	 * 统一下单
	 *
	 * @param UnifiedOrderOptions $input
	 * @return UnifiedOrderResult
	 * @throws PaymentException
	 */
	public function unifiedOrder(UnifiedOrderOptions $input){
		$payType = $input->getPayType();
		$engine = $this->getEngine($payType);
		return $engine->unifiedOrder($input);
	}

	/**
	 * 查询订单
	 *
	 * @param OrderQueryOptions $input
	 * @return OrderQueryResult
	 * @throws PaymentException
	 */
	public function orderQuery(OrderQueryOptions $input){
		$payType = $input->getPayType();
		$engine = $this->getEngine($payType);
		return $engine->orderQuery($input);
	}

	/**
	 * 关闭订单
	 *
	 * @param CloseOrderOptions $input
	 * @return CloseOrderResult
	 * @throws PaymentException
	 */
	public function closeOrder(CloseOrderOptions $input){
		$payType = $input->getPayType();
		$engine = $this->getEngine($payType);
		return $engine->closeOrder($input);
	}

	/**
	 * 申请退款
	 *
	 * @param RefundOptions $input
	 * @return RefundResult
	 * @throws PaymentException
	 */
	public function refund(RefundOptions $input){
		$payType = $input->getPayType();
		$engine = $this->getEngine($payType);
		return $engine->refund($input);
	}

	/**
	 * 退款查询
	 *
	 * @param RefundQueryOptions $input
	 * @return RefundQueryResult
	 * @throws PaymentException
	 */
	public function refundQuery(RefundQueryOptions $input){
		$payType = $input->getPayType();
		$engine = $this->getEngine($payType);
		return $engine->refundQuery($input);
	}

	/**
	 * 撤销订单
	 *
	 * @param ReverseOptions $input
	 * @return ReverseResult
	 * @throws PaymentException
	 */
	public function reverse(ReverseOptions $input){
		$payType = $input->getPayType();
		$engine = $this->getEngine($payType);
		return $engine->reverse($input);
	}

	/**
	 * 验证签名
	 *
	 * @param PaymentResult $result
	 * @throws PaymentException
	 */
	public function checkSign(PaymentResult $result){
		$payType = $result->getPayType();
		$engine = $this->getEngine($payType);
		return $engine->checkSign($result);
	}

	/**
	 * 获取引擎
	 *
	 * @param string $engine
	 * @return \xin\payment\PaymentInterface
	 */
	protected function getEngine($engine){
	}
}
