<?php

namespace Xin\Payment;

use Xin\Capsule\WithConfig;
use Xin\Payment\Contracts\Factory as PaymentFactory;
use Xin\Payment\Exceptions\PaymentNotConfigureException;
use Yansongda\Pay\Contract\ProviderInterface;
use Yansongda\Pay\Pay;

class PaymentManager implements PaymentFactory
{
	use WithConfig, HasWechat, HasAlipay, HasUnipay, HasDouyin;

	/**
	 * @var array
	 */
	protected $config = [];

	/**
	 * Payment constructor.
	 *
	 * @param array $config
	 */
	public function __construct(array $config)
	{
		$this->config = $config;
	}

	/**
	 * @inheritDoc
	 */
	public function wechat($name = null, array $options = [])
	{
		$name = $name ?: $this->getWechatDefaultProvider();

		if (empty($config)) {
			throw new PaymentNotConfigureException("payment config 'wechat.{$name}' not defined.");
		}

		$config = $this->getWechatProviderConfig($name);
		$config = $this->initWechatConfig($config, $options);
		$config = $this->initApplicationConfig($config, $options);

		return $this->initApplication(
			Pay::wechat($config),
			$options
		);
	}

	/**
	 * @inheritDoc
	 */
	public function hasWechat($name = null)
	{
		$name = $name ?: $this->getWechatDefaultProvider();

		return $this->hasWechatProviderConfig($name) &&
			$this->hasWechatProviderConfig($name . '.mch_id') &&
			$this->hasWechatProviderConfig($name . '.key');
	}

	/**
	 * @inheritDoc
	 */
	public function alipay($name = null, array $options = [])
	{
		$name = $name ?: $this->getAlipayDefaultProvider();

		if (empty($config)) {
			throw new PaymentNotConfigureException("payment config 'alipay.{$name}' not defined.");
		}

		$config = $this->getAlipayProviderConfig($name);
		$config = $this->initAlipayConfig($config, $options);
		$config = $this->initApplicationConfig($config, $options);

		return $this->initApplication(
			Pay::alipay($config),
			$options
		);
	}

	/**
	 * @inheritDoc
	 */
	public function hasAlipay($name = null)
	{
		$name = $name ?: $this->getAlipayDefaultProvider();

		return $this->hasAlipayProviderConfig($name);
	}

	/**
	 * @inheritDoc
	 */
	public function unipay($name = null, array $options = [])
	{
		$name = $name ?: $this->getUnipayDefaultProvider();

		if (empty($config)) {
			throw new PaymentNotConfigureException("payment config 'Unipay.{$name}' not defined.");
		}

		$config = $this->getUnipayProviderConfig($name);
		$config = $this->initUnipayConfig($config, $options);
		$config = $this->initApplicationConfig($config, $options);

		return $this->initApplication(
			Pay::unipay($config),
			$options
		);
	}

	/**
	 * @inheritDoc
	 */
	public function hasUnipay($name = null)
	{
		$name = $name ?: $this->getUnipayDefaultProvider();

		return $this->hasUnipayProviderConfig($name);
	}

	/**
	 * @inheritDoc
	 */
	public function douyin($name = null, array $options = [])
	{
		$name = $name ?: $this->getDouyinDefaultProvider();

		if (empty($config)) {
			throw new PaymentNotConfigureException("payment config 'Douyin.{$name}' not defined.");
		}

		$config = $this->getDouyinProviderConfig($name);
		$config = $this->initDouyinConfig($config, $options);
		$config = $this->initApplicationConfig($config, $options);

		return $this->initApplication(
			Pay::Douyin($config),
			$options
		);
	}

	/**
	 * @inheritDoc
	 */
	public function hasDouyin($name = null)
	{
		$name = $name ?: $this->getDouyinDefaultProvider();

		return $this->hasDouyinProviderConfig($name);
	}

	/**
	 * 初始化默认配置
	 * @param array $config
	 * @param array $options
	 * @return array
	 */
	public function initApplicationConfig(array $config, array $options)
	{
		return array_replace_recursive([
			'logger' => $this->getConfig('logger', []),
			'http' => $this->getConfig('http', []),
		], $config);
	}

	/**
	 * 初始化
	 *
	 * @param ProviderInterface $driver
	 * @param array $options
	 * @return ProviderInterface
	 */
	protected function initApplication($driver, array $options = [])
	{
		return $driver;
	}


}
