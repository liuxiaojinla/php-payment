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
use xin\payment\AbsPayment;
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

class Basic extends AbsPayment{

	/**
	 * 引擎列表
	 *
	 * @var array
	 */
	protected $engines = [];

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
		$engine->checkSign($result);
	}

	/**
	 * 获取引擎
	 *
	 * @param string $type
	 * @return \xin\payment\PaymentInterface
	 * @throws \xin\payment\PaymentException
	 */
	protected function getEngine($type){
		if(empty($type)) throw new PaymentException('交易引擎必须填写！');
		$type = strtolower($type);

		$engine = str_replace("_", "", ucwords($type, "_"));
		if(!isset($this->engines[$engine])){
			$class = "\\xin\\payment\\driver\\engine\\{$engine}";
			$options = isset($this->config[$type]) ? $this->config[$type] : [];
			$this->engines[$engine] = new $class($options);
		}
		return $this->engines[$engine];
	}
}
