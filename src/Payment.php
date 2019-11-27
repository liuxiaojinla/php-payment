<?php
/**
 * I know no such things as genius,it is nothing but labor and diligence.
 *
 * @copyright (c) 2015~2019 BD All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author BD<657306123@qq.com>
 */

namespace xin\payment;

use xin\payment\func\CloseOrder;
use xin\payment\func\OrderQuery;
use xin\payment\func\PayBank;
use xin\payment\func\PayBankQuery;
use xin\payment\func\Refund;
use xin\payment\func\RefundQuery;
use xin\payment\func\Reverse;
use xin\payment\func\Transfers;
use xin\payment\func\TransfersQuery;
use xin\payment\func\UnifiedOrder;

/**
 * 统一支付
 * @method \xin\payment\entity\UnifiedOrderOutput unifiedOrder(\xin\payment\entity\UnifiedOrderInput $input) 统一下单
 * @method \xin\payment\entity\OrderQueryOutput orderQuery(\xin\payment\entity\OrderQueryInput $input) 查询订单
 * @method \xin\payment\entity\CloseOrderOutput closeOrder(\xin\payment\entity\CloseOrderInput $input) 关闭订单
 * @method \xin\payment\entity\RefundOutput refund(\xin\payment\entity\RefundInput $input) 申请退款
 * @method \xin\payment\entity\RefundQueryOutput refundQuery(\xin\payment\entity\RefundQueryInput $input) 退款查询
 * @method \xin\payment\entity\ReverseOutput reverse(\xin\payment\entity\ReverseInput $input) 撤销订单
 * @method \xin\payment\entity\TransfersOutput transfers(\xin\payment\entity\TransfersInput $input) 企业打款到零钱
 * @method \xin\payment\entity\TransfersQueryOutput transferQuery(\xin\payment\entity\TransfersQueryInput $input) 企业打款到零钱查询
 * @method \xin\payment\entity\PayBankOutput payBank(\xin\payment\entity\PayBankInput $input) 企业打款到银行卡
 * @method \xin\payment\entity\PayBankQueryOutput payBankQuery(\xin\payment\entity\PayBankQueryInput $input) 企业打款到银行卡查询
 *
 * @package xin\payment
 */
class Payment implements BindFuncInterface{

	/**
	 * 提供者
	 *
	 * @var array
	 */
	protected $providers = [
		UnifiedOrder::class,
		OrderQuery::class,
		CloseOrder::class,
		Refund::class,
		RefundQuery::class,
		Reverse::class,
		Transfers::class,
		TransfersQuery::class,
		PayBank::class,
		PayBankQuery::class,
	];

	/**
	 * @var array
	 */
	protected $functions = [];

	/**
	 * 配置参数
	 *
	 * @var ConfigInterface
	 */
	protected $config = [];

	/**
	 * Payment constructor.
	 *
	 * @param ConfigInterface $config
	 */
	public function __construct(ConfigInterface $config){
		$this->config = $config;

		$this->registerProviders();
	}

	/**
	 * 注册服务
	 */
	private function registerProviders(){
		foreach($this->providers as $provider){
			/** @var \xin\payment\ProviderInterface $provider */
			$provider = new $provider();
			$provider->bindCall($this, $this->config);
		}
	}

	/**
	 * 绑定函数
	 *
	 * @param string $name
	 * @param mixed  $call
	 */
	public function bind($name, $call){
		$this->functions[$name] = $call;
	}

	/**
	 * 自动调用
	 *
	 * @param string $name
	 * @param array  $arguments
	 * @return mixed
	 */
	public function __call($name, $arguments){
		if(isset($this->functions[$name])){
			$func = $this->functions[$name];
			$config = $this->config;
			array_unshift($arguments, $config);

			return call_user_func_array($func, $arguments);
		}

		throw new \BadMethodCallException("{$name} not found!");
	}

}
