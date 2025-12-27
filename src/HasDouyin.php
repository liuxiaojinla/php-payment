<?php

namespace Xin\Payment;

use Xin\Payment\Exceptions\PaymentNotConfigureException;
use Yansongda\Pay\Pay;
use Yansongda\Pay\Provider\Douyin;

trait HasDouyin
{
	/**
	 * @return string
	 */
	protected function getDouyinDefaultProvider()
	{
		return $this->getConfig('defaults.douyin', 'default');
	}

	/**
	 * 获取抖音的配置
	 * @param string $name
	 * @return array|\ArrayAccess|mixed
	 */
	protected function getDouyinProviderConfig($name)
	{
		return $this->getConfig("douyin.{$name}");
	}

	/**
	 * 是否有抖音支付的配置
	 * @param string $name
	 * @return bool
	 */
	protected function hasDouyinProviderConfig($name)
	{
		return $this->hasConfig("douyin.{$name}");
	}

	/**
	 * 初始化抖音配置信息
	 * @param array $config
	 * @param array $options
	 * @return array
	 */
	protected function initDouyinConfig(array $config, array $options)
	{
		return $config;
	}
}
