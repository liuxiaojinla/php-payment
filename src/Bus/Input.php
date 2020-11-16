<?php
/**
 * I know no such things as genius,it is nothing but labor and diligence.
 *
 * @copyright (c) 2015~2019 BD All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author BD<657306123@qq.com>
 */

namespace Xin\Payment\Bus;

use Xin\Payment\Kernel\Support\Str;

/**
 * 请求参数
 */
abstract class Input extends Attribute{
	
	//	/**
	//	 * 设置数据签名
	//	 *
	//	 * @param string|callable $sign
	//	 */
	//	public function setSign($sign){
	//		if(is_callable($sign)){
	//			$sign = call_user_func($sign, $this->data);
	//		}
	//
	//		$this->set('sign', $sign);
	//	}
	
	/**
	 * 获取支付渠道
	 *
	 * @param string $channel
	 * @return string
	 */
	public function setChannel($channel){
		return $this->channel = $channel;
	}
	
	/**
	 * 移除字段
	 *
	 * @param string $key
	 */
	public function remove($key){
		unset($this->data[$key]);
	}
	
	/**
	 * 设置配置项
	 *
	 * @param string $name
	 * @param mixed  $value
	 * @return static
	 */
	public function set($name, $value){
		$this->data[$name] = $value;
		return $this;
	}
	
	/**
	 * 生成输出实例
	 *
	 * @param mixed $output
	 * @param array $config
	 * @return \Xin\Payment\Bus\Output
	 */
	public function makeOutput($output, $config = []){
		$outputClass = substr(get_class($this), 0, -5)."Output";
		if(!class_exists($outputClass)){
			return $output;
		}
		
		if(method_exists($outputClass, '__make')){
			return call_user_func([$outputClass, '__make'], $output, $this, $config);
		}
		
		return new $outputClass($output, $this, $config, $output);
	}
	
	/**
	 * 设置数据
	 *
	 * @param string $name
	 * @param mixed  $value
	 */
	public function __set($name, $value){
		$this->set($name, $value);
	}
	
	/**
	 * 移除字段
	 *
	 * @param string $name
	 */
	public function __unset($name){
		$this->remove($name);
	}
	
	/**
	 * 动态调用函数
	 *
	 * @param string $name
	 * @param array  $arguments
	 * @return mixed
	 */
	public function __call($name, $arguments){
		$prefix = substr($name, 0, 3);
		$key = Str::snake(substr($name, 3));
		
		if('get' == $prefix){
			$default = isset($arguments[0]) ? $arguments[0] : null;
			return $this->get($key, $default);
		}elseif('set' == $prefix){
			return $this->set($key, $arguments[0]);
		}elseif('has'){
			return $this->has($key);
		}
		
		throw new \BadMethodCallException("{$name}方法不存在！");
	}
	
	/**
	 * Offset to set
	 *
	 * @link https://php.net/manual/en/arrayaccess.offsetset.php
	 * @param mixed $offset <p>
	 * The offset to assign the value to.
	 * </p>
	 * @param mixed $value <p>
	 * The value to set.
	 * </p>
	 * @return void
	 * @since 5.0.0
	 */
	public function offsetSet($offset, $value){
		$this->set($offset, $value);
	}
	
	/**
	 * Offset to unset
	 *
	 * @link https://php.net/manual/en/arrayaccess.offsetunset.php
	 * @param mixed $offset <p>
	 * The offset to unset.
	 * </p>
	 * @return void
	 * @since 5.0.0
	 */
	public function offsetUnset($offset){
		$this->remove($offset);
	}
}
