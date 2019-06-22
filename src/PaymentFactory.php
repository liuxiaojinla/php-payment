<?php
/**
 * I know no such things as genius,it is nothing but labor and diligence.
 *
 * @copyright (c) 2015~2019 BD All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author BD<657306123@qq.com>
 */

namespace xin\payment;

use BadMethodCallException;
use RuntimeException;

/**
 * 支付API工厂
 * @method string getAppId() 获取AppId
 * @method \xin\payment\driver\Basic factoryBasic(array $options) static 获取默认的聚合支付实例
 *
 * @package xin\payment
 */
abstract class PaymentFactory implements PaymentInterface{

	/**
	 * 静态调用处理
	 *
	 * @param string $name
	 * @param array  $arguments
	 * @return mixed
	 */
	public static function __callStatic($name, $arguments){
		if(strpos($name, "factory") === 0){
			$driver = substr($name, 7);
			return self::factory($driver, $arguments[0]);
		}
		throw new BadMethodCallException("{$name}方法不存在！");
	}

	/**
	 * 构聚合建支付API实例
	 *
	 * @param string $driver
	 * @param array  $options
	 * @return mixed
	 */
	public static function factory($driver, $options = []){
		if(stripos($driver, '\\') !== 0){
			$driver = "\\xin\\payment\\driver\\{$driver}";
		}

		if(!class_exists($driver)) throw new RuntimeException("支付驱动不存在：{$driver}");
		return new $driver($options);
	}
}
