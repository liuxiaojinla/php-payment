<?php
/**
 * I know no such things as genius,it is nothing but labor and diligence.
 *
 * @copyright (c) 2015~2019 BD All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author BD<657306123@qq.com>
 */
namespace Xin\Payment\Bus;

use Xin\Payment\Exceptions\MissingParameterException;
use Xin\Payment\Support\Arr;
use Xin\Payment\Support\Str;

/**
 * 支付参数基类
 */
abstract class Attribute implements \ArrayAccess, \IteratorAggregate, \JsonSerializable{
	
	/**
	 * @var string
	 */
	protected $channel = 'wechat';
	
	/**
	 * @var array
	 */
	protected $data = [];
	
	/**
	 * PaymentData constructor.
	 *
	 * @param array $data
	 */
	public function __construct(array $data = []){
		$this->data = $data;
	}
	
	/**
	 * 获取支付渠道
	 *
	 * @return string
	 */
	public function getChannel(){
		return $this->channel;
	}
	
	/**
	 * 检查参数是否存在
	 *
	 * @param array $keys
	 * @param bool  $failException
	 * @return bool
	 * @throws \Xin\Payment\Exceptions\MissingParameterException
	 */
	public function check($keys, $failException = true){
		foreach($keys as $key){
			if(!isset($this->data[$key])){
				if($failException){
					throw new MissingParameterException("缺少参数{$key}！");
				}
				
				return false;
			}
		}
		return true;
	}
	
	/**
	 * 获取支付签名
	 *
	 * @return string
	 */
	public function getSign(){
		return $this->get('sign');
	}
	
	/**
	 * 数据签名是否存在
	 *
	 * @return bool
	 */
	public function hasSign(){
		return $this->has('sign');
	}
	
	/**
	 * 获取原始数据
	 *
	 * @param array $keysMap
	 * @return array
	 */
	public function toArray(array $keysMap = []){
		return Arr::transformKeys($this->data, $keysMap);
	}
	
	/**
	 * 转换相关的键名并返回全新的实例
	 *
	 * @param array $keysMap
	 * @return static
	 */
	public function withTransformKeys(array $keysMap = []){
		$clone = clone $this;
		$clone->data = Arr::transformKeys($clone->data, $keysMap);
		
		return $clone;
	}
	
	/**
	 * 输出xml字符
	 *
	 * @param array $keysMap
	 * @return string
	 */
	public function toXml(array $keysMap = []){
		$data = $this->toArray($keysMap);
		
		$xml = "<xml>";
		foreach($data as $key => $val){
			if(is_numeric($val)){
				$xml .= "<".$key.">".$val."</".$key.">";
			}else{
				$xml .= "<".$key."><![CDATA[".$val."]]></".$key.">";
			}
		}
		$xml .= "</xml>";
		
		return $xml;
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
	 * 判断指定的key是否存在，并且是否为空
	 *
	 * @param string $key
	 * @return bool
	 */
	public function hasEmpty($key){
		return !isset($this->data[$key]) || empty($this->data[$key]);
	}
	
	/**
	 * 配置是否存在
	 *
	 * @param string|array $key
	 * @return bool
	 */
	public function has($key){
		if(is_array($key)){
			foreach($key as $k){
				if(isset($this->data[$k])){
					return true;
				}
			}
		}else{
			if(isset($this->data[$key])){
				return true;
			}
		}
		return false;
	}
	
	/**
	 * 获取配置项
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	public function get($key, $default = null){
		return Arr::get($this->data, $key, $default);
	}
	
	/**
	 * 检查字段是否存在
	 *
	 * @param string $name
	 * @return bool
	 */
	public function __isset($name){
		return isset($this->data[$name]);
	}
	
	/**
	 * 获取字段
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name){
		return $this->data[$name];
	}
	
	/**
	 * Retrieve an external iterator
	 *
	 * @link https://php.net/manual/en/iteratoraggregate.getiterator.php
	 * @return \ArrayIterator An instance of an object implementing <b>Iterator</b> or
	 * <b>Traversable</b>
	 * @since 5.0.0
	 */
	public function getIterator(){
		return new \ArrayIterator($this->data);
	}
	
	/**
	 * Whether a offset exists
	 *
	 * @link https://php.net/manual/en/arrayaccess.offsetexists.php
	 * @param mixed $offset <p>
	 * An offset to check for.
	 * </p>
	 * @return boolean true on success or false on failure.
	 * </p>
	 * <p>
	 * The return value will be casted to boolean if non-boolean was returned.
	 * @since 5.0.0
	 */
	public function offsetExists($offset){
		return isset($this->data[$offset]);
	}
	
	/**
	 * Offset to retrieve
	 *
	 * @link https://php.net/manual/en/arrayaccess.offsetget.php
	 * @param mixed $offset <p>
	 * The offset to retrieve.
	 * </p>
	 * @return mixed Can return all value types.
	 * @since 5.0.0
	 */
	public function offsetGet($offset){
		return $this->get($offset);
	}
	
	/**
	 * Count elements of an object
	 *
	 * @link https://php.net/manual/en/countable.count.php
	 * @return int The custom count as an integer.
	 * </p>
	 * <p>
	 * The return value is cast to an integer.
	 * @since 5.1.0
	 */
	public function count(){
		return count($this->data);
	}
	
	/**
	 * Specify data which should be serialized to JSON
	 *
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize(){
		return $this->toArray();
	}
	
}
