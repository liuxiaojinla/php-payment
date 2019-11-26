<?php
/**
 * I know no such things as genius,it is nothing but labor and diligence.
 *
 * @copyright (c) 2015~2019 BD All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author BD<657306123@qq.com>
 */

namespace xin\payment;

use xin\payment\entity\CloseOrderOptions;
use xin\payment\entity\CloseOrderResult;
use xin\payment\entity\OrderQueryOptions;
use xin\payment\entity\OrderQueryResult;
use xin\payment\entity\RefundOptions;
use xin\payment\entity\RefundQueryOptions;
use xin\payment\entity\RefundQueryResult;
use xin\payment\entity\RefundResult;
use xin\payment\entity\ReverseOptions;
use xin\payment\entity\ReverseResult;
use xin\payment\entity\UnifiedOrderOptions;
use xin\payment\entity\UnifiedOrderResult;
use xin\payment\func\OrderQuery;
use xin\payment\func\UnifiedOrder;

/**
 * 统一支付
 * @method UnifiedOrderResult unifiedOrder(UnifiedOrderOptions $input) 统一下单
 * @method OrderQueryResult orderQuery(OrderQueryOptions $input) 查询订单
 * @method CloseOrderResult closeOrder(CloseOrderOptions $input) 关闭订单
 * @method RefundResult refund(RefundOptions $input) 申请退款
 * @method RefundQueryResult refundQuery(RefundQueryOptions $input) 退款查询
 * @method ReverseResult reverse(ReverseOptions $input) 撤销订单
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
		OrderQuery::class,
		UnifiedOrder::class,
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

	//	/**
	//	 * 获取配置
	//	 *
	//	 * @param string $key
	//	 * @param mixed  $default
	//	 * @return mixed
	//	 */
	//	public function getConfig($key, $default = null){
	//		return isset($this->config[$key]) ? $this->config[$key] : $default;
	//	}
	//
	//	/**
	//	 * 配置是否存在
	//	 *
	//	 * @param string $key
	//	 * @return bool
	//	 */
	//	public function hasConfig($key){
	//		return isset($this->config[$key]);
	//	}

	//	/**
	//	 * 静态调用处理
	//	 *
	//	 * @param string $name
	//	 * @param array  $arguments
	//	 * @return mixed
	//	 */
	//	public static function __callStatic($name, $arguments){
	//		if(in_array($name, [
	//			'unifiedOrder',
	//			'orderQuery',
	//			'closeOrder',
	//			'refund',
	//			'refundQuery',
	//			'reverse',
	//		])){
	//			$options = array_shift($arguments);
	//			return call_user_func_array([
	//				self::factory($name, $options),
	//				$name,
	//			], $arguments);
	//		}
	//		return self::factory($name, $arguments[0]);
	//		//		if(strpos($name, "factory") === 0){
	//		//			$driver = substr($name, 7);
	//		//			return self::factory($driver, $arguments[0]);
	//		//		}
	//		//		throw new BadMethodCallException("{$name}方法不存在！");
	//	}
	//
	//	/**
	//	 * 构聚合建支付API实例
	//	 *
	//	 * @param string $driver
	//	 * @param array  $options
	//	 * @return mixed
	//	 */
	//	public static function factory($driver, $options = []){
	//		if(stripos($driver, '\\') !== 0){
	//			$driver = "\\xin\\payment\\driver\\{$driver}";
	//		}
	//
	//		if(!class_exists($driver)){
	//			throw new RuntimeException("支付驱动不存在：{$driver}");
	//		}
	//
	//		return new $driver($options);
	//	}
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
