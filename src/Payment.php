<?php
/**
 * The following code, none of which has BUG.
 *
 * @author: BD<liuxingwu@duoguan.com>
 * @date: 2019/6/3 17:01
 */

namespace xin\payment;

/**
 * Class Payment
 *
 * @package xin\payment
 */
abstract class Payment{

	/**
	 * 配置参数
	 *
	 * @var array
	 */
	protected $config = [];

	/**
	 * Payment constructor.
	 *
	 * @param array $config
	 */
	public function __construct(array $config){
		$this->config = $config;
	}

	/**
	 * 获取配置
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	public function getConfig($key, $default = null){
		return isset($this->config[$key]) ? $this->config[$key] : $default;
	}

	/**
	 * 配置是否存在
	 *
	 * @param string $key
	 * @return bool
	 */
	public function hasConfig($key){
		return isset($this->config[$key]);
	}
}
