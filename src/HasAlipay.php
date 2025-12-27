<?php

namespace Xin\Payment;

trait HasAlipay
{
	/**
	 * @return string
	 */
	protected function getAlipayDefaultProvider()
	{
		return $this->getConfig('defaults.alipay', 'default');
	}

	/**
	 * 获取支付宝的配置
	 * @param string $name
	 * @return array|\ArrayAccess|mixed
	 */
	protected function getAlipayProviderConfig($name)
	{
		return $this->getConfig("alipay.{$name}");
	}

	/**
	 * 是否有支付宝支付的配置
	 * @param string $name
	 * @return bool
	 */
	protected function hasAlipayProviderConfig($name)
	{
		return $this->hasConfig("alipay.{$name}");
	}

	/**
	 * 初始化支付宝配置信息
	 * @param array $config
	 * @param array $options
	 * @return array
	 */
	protected function initAlipayConfig(array $config, array $options)
	{
		return $config;
	}
}
