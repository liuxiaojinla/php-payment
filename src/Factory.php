<?php
/**
 * Talents come from diligence, and knowledge is gained by accumulation.
 *
 * @author: 晋<657306123@qq.com>
 */

namespace Xin\Payment;

use Xin\Payment\Bus\Input;
use Xin\Payment\Kernel\Support\Arr;
use Xin\Payment\Kernel\Support\Str;

/**
 * Class Factory.
 * @method static $this makeEasyPay(array $config)
 * @method \Xin\Payment\Bus\Base\UnifiedOrderOutput unifiedOrder(\Xin\Payment\Bus\Base\UnifiedOrderInput $input)
 * @method \Xin\Payment\Bus\Base\OrderQueryOutput orderQuery(\Xin\Payment\Bus\Base\OrderQueryInput $input)
 * @method \Xin\Payment\Bus\Base\RefundOutput refund(\Xin\Payment\Bus\Base\RefundInput $input)
 * @method \Xin\Payment\Bus\Base\RefundQueryOutput refundQuery(\Xin\Payment\Bus\Base\RefundQueryInput $input)
 * @method \Xin\Payment\Bus\Base\CloseOrderOutput closeOrder(\Xin\Payment\Bus\Base\CloseOrderInput $input)
 * @method \Xin\Payment\Bus\Base\ReverseOutput reverse(\Xin\Payment\Bus\Base\ReverseInput $input)
 * @method \Xin\Payment\Bus\Transfer\TransfersOutput transfer(\Xin\Payment\Bus\Transfer\TransfersOutput $input)
 * @method \Xin\Payment\Bus\Transfer\TransfersQueryOutput transferQuery(\Xin\Payment\Bus\Transfer\TransfersQueryOutput $input)
 */
class Factory{
	
	/**
	 * @var static
	 */
	protected static $defaultInstance = null;
	
	/**
	 * @var array
	 */
	protected $config = [
		'defaults' => [
			'adapter' => 'base',
		],
		
		'channels' => [
			'wxpay' => [
			
			],
			
			'alipay' => [
			
			],
		],
	];
	
	/**
	 * @var \Xin\Payment\Adapters\AbstractAdapter
	 */
	protected $adapters = [];
	
	/**
	 * @var array
	 */
	protected $converters = [];
	
	/**
	 * Factory constructor.
	 *
	 * @param array $config
	 */
	protected function __construct(array $config){
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
	 * 选择默认适配器
	 *
	 * @param string $name
	 */
	public function shouldUseAdapter($name){
		Arr::set($this->config, 'defaults.adapter', $name);
		$this->adapter();
	}
	
	/**
	 * 获取适配器实例
	 *
	 * @param string|null $name
	 * @return \Xin\Payment\Adapters\AbstractAdapter
	 */
	protected function adapter($name = null){
		$name = $name ? $name : $this->getDefaultAdapter();
		return $this->resolveAdapter($name);
	}
	
	/**
	 * 获取默认的适配器
	 *
	 * @return string
	 */
	protected function getDefaultAdapter(){
		return $this->config('defaults.adapter');
	}
	
	/**
	 * 解析适配器
	 *
	 * @param string $name
	 * @return \Xin\Payment\Adapters\AbstractAdapter
	 */
	protected function resolveAdapter($name){
		if(!isset($this->adapters[$name])){
			$this->adapters[$name] = $this->createAdapter($name);
		}
		
		return $this->adapters[$name];
	}
	
	/**
	 * 创建适配器
	 *
	 * @param string $name
	 * @return \Xin\Payment\Adapters\AbstractAdapter
	 */
	protected function createAdapter($name){
		$className = Str::studly($name);
		$realClass = "\\Xin\\Payment\\Adapters\\{$className}Adapter";
		
		return new $realClass($this->config('channels'));
	}
	
	/**
	 * 解析转换器
	 *
	 * @param string $name
	 * @param string $channel
	 * @return \Xin\Payment\Kernel\Contracts\Converter
	 */
	protected function resolveConverter($name, $channel){
		if(!isset($this->converters[$channel])){
			$this->converters[$channel] = [];
		}
		
		if(!isset($this->converters[$channel][$name])){
			$this->converters[$channel][$name] = $this->createConverter($name, $channel);
		}
		
		return $this->converters[$channel][$name];
	}
	
	/**
	 * 解析转换器
	 *
	 * @param string $name
	 * @param string $channel
	 * @return \Xin\Payment\Kernel\Contracts\Converter
	 */
	protected function createConverter($name, $channel){
		$channelPath = Str::studly($channel);
		$className = Str::studly($name);
		$realClass = "\\Xin\\Payment\\Converters\\{$channelPath}\\{$className}Converter";
		
		if(!class_exists($realClass)){
			return null;
		}
		
		return new $realClass($this);
	}
	
	/**
	 * @param string $name
	 * @param array  $arguments
	 * @return mixed
	 */
	public function __call($name, $arguments){
		/** @var \Xin\Payment\Bus\Input $input */
		$input = isset($arguments[0]) ? $arguments[0] : null;
		if(!$input instanceof Input){
			throw new \RuntimeException("call function first arguments must of [\Xin\Payment\Bus\Input] type.");
		}
		
		$channel = $input->getChannel();
		
		$converter = $this->resolveConverter($name, $channel);
		
		if($converter){
			$arguments[0] = $converter->convertInput($input);
		}
		
		$output = call_user_func_array([$this->adapter(), $name], $arguments);
		
		if($converter){
			$output = $converter->convertOutput($output, $input, $this->config);
		}else{
			$output = $input->makeOutput($output, $this->config);
		}
		
		return $output;
	}
	
	/**
	 * 制作支付器
	 *
	 * @param array       $config
	 * @param string|null $name
	 * @return static
	 */
	public static function make($config, $name = null){
		return static::$defaultInstance = new static([
			'defaults' => [
				'adapter' => $name ? $name : "Base",
			],
			'channels' => $config,
		]);
	}
	
	/**
	 * Dynamically pass methods to the application.
	 *
	 * @param string $name
	 * @param array  $arguments
	 * @return mixed
	 */
	public static function __callStatic($name, $arguments){
		if(strpos($name, "make") === 0){
			return static::make($arguments[0], $name);
		}
		
		return call_user_func_array([
			static::$defaultInstance, $name,
		], $arguments);
	}
	
}
