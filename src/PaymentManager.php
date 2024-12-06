<?php

namespace Xin\Payment;

use Xin\Capsule\WithConfig;
use Xin\Payment\Contracts\Factory as PaymentFactory;
use Xin\Support\Arr;

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

		return $this->makeWechat($this->getWechatProviderConfig($name), $options, $name);
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

		return $this->makeAlipay($this->getAlipayProviderConfig($name), $options, $name);
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

		return $this->makeAlipay($this->getUnipayProviderConfig($name), $options, $name);
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

		return $this->makeAlipay($this->getDouyinProviderConfig($name), $options, $name);
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
	 * 指定类型的配置是否存在
	 *
	 * @param string $key
	 * @return bool
	 */
	public function hasConfig($key = null)
	{
		if ($key == null) {
			return !empty($this->config);
		}

		return Arr::has($this->config, $key);
	}

	/**
	 * 初始化
	 *
	 * @param mixed $driver
	 * @param array $options
	 * @return mixed
	 */
	protected function initApplication($driver, array $options = [])
	{
		return $driver;
	}


}
