<?php

namespace Xin\Payment;

use Xin\Payment\Exceptions\PaymentNotConfigureException;
use Xin\Support\File;
use Yansongda\Pay\Pay;
use Yansongda\Pay\Provider\Wechat;

trait HasWechat
{
	/**
	 * @return string
	 */
	protected function getWechatDefaultProvider()
	{
		return $this->getConfig('defaults.wechat', 'default');
	}

	/**
	 * 获取微信支付的配置
	 * @param string $name
	 * @return array|\ArrayAccess|mixed
	 */
	protected function getWechatProviderConfig($name)
	{
		return $this->getConfig("wechat.{$name}");
	}

	/**
	 * 是否有微信支付的配置
	 * @param string $name
	 * @return bool
	 */
	protected function hasWechatProviderConfig($name)
	{
		return $this->hasConfig("wechat.{$name}");
	}

	/**
	 * 构建微信支付实例
	 * @param array $config
	 * @param array $options
	 * @param string|null $providerName
	 * @return Wechat
	 */
	protected function makeWechat($config, array $options = [], string $providerName = null)
	{
		if (empty($config)) {
			throw new PaymentNotConfigureException("payment config 'wechat.{$providerName}' not defined.");
		}

		$config = $this->initWechatConfig($config, $options);
		$config = $this->initApplicationConfig($config, $options);

		return $this->initApplication(
			Pay::wechat($config),
			$options
		);
	}


	/**
	 * 初始化微信配置信息
	 *
	 * @param array $config
	 * @return array
	 */
	protected function initWechatConfig($config, $options)
	{
		if (isset($config['appid'])) {
			// fix official
			if (!isset($config['app_id'])) {
				$config['app_id'] = $config['appid'];
			}

			// fix miniapp
			if (!isset($config['miniapp_id'])) {
				$config['miniapp_id'] = $config['appid'];
			}
		}

		// cert support
		if (isset($options['cert'])) {
			if (isset($config['cert_client_content'])) {
				$config['cert_client'] = File::putTempFile($config['cert_client_content']);
			}

			if (isset($config['cert_key_content'])) {
				$config['cert_key'] = File::putTempFile($config['cert_key_content']);
			}
		}

		return $config;
	}
}
