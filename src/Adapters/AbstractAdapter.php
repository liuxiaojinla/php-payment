<?php
/**
 * Talents come from diligence, and knowledge is gained by accumulation.
 *
 * @author: 晋<657306123@qq.com>
 */

namespace Xin\Payment\Adapters;

use Xin\Payment\Bus\Input;
use Xin\Payment\Kernel\Support\Arr;

abstract class AbstractAdapter{
	
	/**
	 * @var array
	 */
	protected $config = [];
	
	/**
	 * @var string
	 */
	protected $gateway = 'wechat';
	
	/**
	 * @var array
	 */
	protected $gateways = [];
	
	/**
	 * @var array
	 */
	protected $realMethodMap = [];
	
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
	 * 获取支付器实例
	 *
	 * @param string|null $gateway
	 * @return mixed
	 */
	public function gateway($gateway = null){
		$gateway = $gateway ? $gateway : $this->gateway;
		
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
	 * 选择并解析支付网关器
	 *
	 * @param string $gateway
	 */
	public function shouldUse($gateway){
		$this->gateway = $gateway;
		
		$this->resolveGateway($gateway);
	}
	
	/**
	 * 获取微信支付器实例
	 *
	 * @return $this
	 */
	public function useWechat(){
		$this->shouldUse('wechat');
		
		return $this->gateway();
	}
	
	/**
	 * 获取支付宝支付器实例
	 *
	 * @return $this
	 */
	public function useAlipay(){
		$this->shouldUse('alipay');
		
		return $this->gateway();
	}
	
	/**
	 * 解析入口参数
	 *
	 * @param string $name
	 * @param array  $arguments
	 * @return mixed
	 */
	protected function parseInput($name, array $arguments){
		$input = isset($arguments[0]) ? $arguments[0] : null;
		if($input instanceof Input){
			$arguments[0] = $input->toArray();
		}
		
		return $arguments;
	}
	
	/**
	 * 解析输出数据
	 *
	 * @param string $name
	 * @param mixed  $result
	 * @param array  $arguments
	 * @return mixed
	 */
	protected function parseOutput($name, $result, array $arguments){
		return $result;
	}
	
	/**
	 * @param \Exception $e
	 * @return \Exception
	 */
	protected function castException(\Exception $e){
		return $e;
	}
	
	/**
	 * @param string $name
	 * @param array  $arguments
	 * @return mixed
	 * @throws \Exception
	 */
	public function __call($name, $arguments){
		/** @var Input $input */
		$input = isset($arguments[0]) ? $arguments[0] : null;
		$channel = $input ? $input->getChannel() : null;
		$realMethod = $this->realMethodName($name, $input);
		
		$arguments = $this->parseInput($name, $arguments);
		
		try{
			$result = call_user_func_array([
				$this->gateway($channel), $realMethod,
			], $arguments);
		}catch(\Exception $e){
			throw $this->castException($e);
		}
		
		return $this->parseOutput($name, $result, $arguments);
	}
	
	/**
	 * @param string $name
	 * @param Input  $input
	 * @return mixed
	 */
	protected function realMethodName($name, $input){
		return isset($this->realMethodMap[$name])
			? $this->realMethodMap[$name] : $name;
	}
	
}
