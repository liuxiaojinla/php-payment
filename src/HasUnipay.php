<?php

namespace Xin\Payment;

use Xin\Payment\Exceptions\PaymentNotConfigureException;
use Yansongda\Pay\Pay;
use Yansongda\Pay\Provider\Unipay;

trait HasUnipay
{
	/**
	 * @return string
	 */
	protected function getUnipayDefaultProvider()
	{
		return $this->getConfig('defaults.unipay', 'default');
	}

	/**
	 * 获取银联的配置
	 * @param string $name
	 * @return array|\ArrayAccess|mixed
	 */
	protected function getUnipayProviderConfig($name)
	{
		return $this->getConfig("unipay.{$name}");
	}

	/**
	 * 是否有银联支付的配置
	 * @param string $name
	 * @return bool
	 */
	protected function hasUnipayProviderConfig($name)
	{
		return $this->hasConfig("unipay.{$name}");
	}

	/**
	 * 构建银联实例
	 * @param array $config
	 * @param array $options
	 * @param string|null $providerName
	 * @return Unipay
	 */
	protected function makeUnipay(array $config, array $options, string $providerName = null)
	{
		if (empty($config)) {
			throw new PaymentNotConfigureException("payment config 'Unipay.{$providerName}' not defined.");
		}

		$config = $this->initUnipayConfig($config, $options);
		$config = $this->initApplicationConfig($config, $options);

		return $this->initApplication(
			Pay::unipay($config),
			$options
		);
	}

	/**
	 * 初始化银联配置信息
	 * @param array $config
	 * @param array $options
	 * @return array
	 */
	protected function initUnipayConfig(array $config, array $options)
	{
		return $config;
	}
}
