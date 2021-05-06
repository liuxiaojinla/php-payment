<?php
/**
 * Talents come from diligence, and knowledge is gained by accumulation.
 *
 * @author: 晋<657306123@qq.com>
 */

namespace Xin\Payment\Adapters;

use Xin\Payment\Bus\Input;
use Xin\Payment\Contracts\Adapter;
use Xin\Payment\Support\Arr;

abstract class AbstractAdapter implements Adapter{

	/**
	 * @var array
	 */
	protected $config = [];

	/**
	 * @var array
	 */
	protected $gateways = [];

	/**
	 * @var array
	 */
	protected $realCommonMethodMap = [];

	/**
	 * @var array
	 */
	protected $realMethodMaps = [];

	/**
	 * AbstractAdapter constructor.
	 *
	 * @param array $config
	 */
	public function __construct(array $config){
		$this->config = $config;
	}

	/**
	 * 获取配置信息
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return array|mixed|null
	 */
	public function config($key, $default = null){
		return Arr::get($this->config, $key, $default);
	}

	/**
	 * 获取微信支付器实例
	 */
	public function wechatGateway(){
		$this->gateway('wechat');
	}

	/**
	 * 获取支付宝支付器实例
	 */
	public function alipayGateway(){
		$this->gateway('alipay');
	}

	/**
	 * 获取支付器实例
	 *
	 * @param string|null $gateway
	 * @return mixed
	 */
	public function gateway($gateway){
		return $this->resolveGateway($gateway);
	}

	/**
	 * 解析支付网关器
	 *
	 * @param string $gateway
	 * @return mixed
	 */
	protected function resolveGateway($gateway){
		if(!isset($this->gateways[$gateway])){
			$this->gateways[$gateway] = $this->createGateway($gateway);
		}

		return $this->gateways[$gateway];
	}

	/**
	 * 解析支付网关器
	 *
	 * @param string $gateway
	 * @return mixed
	 */
	abstract protected function createGateway($gateway);

	/**
	 * 解析入口参数
	 *
	 * @param string                 $channel
	 * @param string                 $action
	 * @param \Xin\Payment\Bus\Input $input
	 * @param array                  $arguments
	 * @return mixed
	 */
	protected function parseArguments($channel, $action, Input $input, array $arguments){
		array_unshift($arguments, $input->toArray());
		return $arguments;
	}

	/**
	 * 解析输出数据
	 *
	 * @param string $channel
	 * @param string $action
	 * @param mixed  $result
	 * @param array  $arguments
	 * @return mixed
	 */
	protected function parseOutput($channel, $action, $result, array $arguments){
		return $result;
	}

	/**
	 * @param \Exception $e
	 * @return mixed
	 */
	protected function castException(\Exception $e){
		return $e;
	}

	/**
	 * @inheritDoc
	 */
	public function execute($action, Input $input, ...$arguments){
		$channel = $input->getChannel();
		$realMethod = $this->resolveRealMethodName($channel, $action, $input);
		$arguments = $this->parseArguments($channel, $action, $input, $arguments);

		try{
			$result = call_user_func_array([
				$this->gateway($channel), $realMethod,
			], $arguments);
		}catch(\Exception $e){
			throw $this->castException($e);
		}

		return $this->parseOutput($channel, $action, $result, $arguments);
	}

	/**
	 * 解析真实要调用的方法名
	 *
	 * @param string $channel
	 * @param string $action
	 * @param Input  $input
	 * @return mixed
	 */
	protected function resolveRealMethodName($channel, $action, Input $input){
		if(isset($this->realMethodMaps[$channel])){
			if(isset($this->realMethodMaps[$action])){
				return $this->realMethodMaps[$action];
			}
		}

		return isset($this->realCommonMethodMap[$action])
			? $this->realCommonMethodMap[$action] : $action;
	}

}
