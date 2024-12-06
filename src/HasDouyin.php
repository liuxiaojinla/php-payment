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
	 * 构建抖音实例
	 * @param array $config
	 * @param array $options
	 * @param string|null $providerName
	 * @return Douyin
	 */
	protected function makeDouyin(array $config, array $options, string $providerName = null)
	{
		if (empty($config)) {
			throw new PaymentNotConfigureException("payment config 'Douyin.{$providerName}' not defined.");
		}

		$config = $this->initDouyinConfig($config, $options);

		$config = array_merge($this->getConfig('defaults'), $config);

		return $this->initApplication(
			Pay::Douyin($config),
			$options
		);
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
