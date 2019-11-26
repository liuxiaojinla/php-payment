<?php
/**
 * I know no such things as genius,it is nothing but labor and diligence.
 *
 * @copyright (c) 2015~2019 BD All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author BD<657306123@qq.com>
 */
namespace xin\payment\entity;

use xin\payment\PaymentException;
use xin\payment\PayType;
use xin\payment\Util;

/**
 * 支付参数基类
 *
 * @package xin\payment
 */
class PaymentData implements \ArrayAccess, \IteratorAggregate, \JsonSerializable{

	/**
	 * @var array
	 */
	protected $data = [];

	/**
	 * PaymentData constructor.
	 *
	 * @param array $data
	 */
	public function __construct(array $data = []){ $this->data = $data; }

	/**
	 * 检查参数是否存在
	 *
	 * @param array $keys
	 * @param bool  $failException
	 * @return bool
	 * @throws \xin\payment\PaymentException
	 */
	public function check($keys, $failException = true){
		foreach($keys as $key){
			if(!isset($this->data[$key])){
				if($failException) throw new PaymentException("缺少统一支付接口必填参数{$key}！");
				return false;
			}
		}
		return true;
	}

	/**
	 * 转换数据
	 *
	 * @param array $keysMap
	 * @return static
	 */
	public function transformKeys(array $keysMap){
		$class = clone $this;
		$class->data = Util::transformKeys($class->data, $keysMap);
		return $class;
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
	 * 输出xml字符
	 *
	 * @return string
	 **@throws PaymentException
	 */
	public function toXml(){
		if(count($this->data) <= 0){
			throw new PaymentException("数组数据异常！");
		}

		$xml = "<xml>";
		foreach($this->data as $key => $val){
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
	 * 获取原始数据
	 *
	 * @return array
	 */
	public function toArray(){
		return $this->data;
	}

	/**
	 * 获取支付引擎类型
	 *
	 * @return string
	 */
	public function getPayType(){
		return $this->get(PayType::__NAME__);
	}

	/**
	 * 设置支付引擎类型
	 *
	 * @param string $payType
	 */
	public function setPayType($payType){
		$this->set(PayType::__NAME__, $payType);
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
		$key = Util::snake(substr($name, 3));
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
			if(isset($this->data[$key])) return true;
		}
		return false;
	}

	/**
	 * 获取配置项
	 *
	 * @param string|array $key
	 * @param mixed        $default
	 * @return mixed
	 */
	public function get($key, $default = null){
		if(is_array($key)){
			foreach($key as $k){
				if(isset($this->data[$k])){
					return $this->data[$k];
				}
			}
		}else{
			if(isset($this->data[$key])) return $this->data[$key];
		}
		return $default;
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
	 * 移除字段
	 *
	 * @param string $key
	 */
	public function remove($key){
		unset($this->data[$key]);
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
