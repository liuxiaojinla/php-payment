<?php

namespace Xin\Payment;

use Xin\Payment\Exceptions\PaymentNotConfigureException;
use Yansongda\Pay\Pay;
use Yansongda\Pay\Provider\Alipay;

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
	 * 构建支付宝实例
	 * @param array $config
	 * @param array $options
	 * @param string|null $providerName
	 * @return Alipay
	 */
	protected function makeAlipay(array $config, array $options, string $providerName = null)
	{
		if (empty($config)) {
			throw new PaymentNotConfigureException("payment config 'alipay.{$providerName}' not defined.");
		}

		$config = $this->initAlipayConfig($config, $options);

		$config = array_merge($this->getConfig('defaults'), $config);

		return $this->initApplication(
			Pay::alipay($config),
			$options
		);
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
