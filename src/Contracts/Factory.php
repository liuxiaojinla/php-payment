<?php

namespace Xin\Payment\Contracts;

use Yansongda\Pay\Provider\Alipay;
use Yansongda\Pay\Provider\Douyin;
use Yansongda\Pay\Provider\Unipay;
use Yansongda\Pay\Provider\Wechat;

interface Factory
{

	/**
	 * 微信支付
	 *
	 * @param string $name
	 * @param array $options
	 * @return Wechat
	 */
	public function wechat($name = null, array $options = []);

	/**
	 * 是否配置微信支付
	 *
	 * @return bool
	 */
	public function hasWechat($name = null);

	/**
	 * 支付宝支付
	 *
	 * @param string $name
	 * @param array $options
	 * @return Alipay
	 */
	public function alipay($name = null, array $options = []);

	/**
	 * 是否配置支付宝支付
	 *
	 * @return bool
	 */
	public function hasAlipay($name = null);

	/**
	 * 银联支付
	 *
	 * @param string $name
	 * @param array $options
	 * @return Unipay
	 */
	public function unipay($name = null, array $options = []);

	/**
	 * 是否配置银联支付
	 *
	 * @return bool
	 */
	public function hasUnipay($name = null);

	/**
	 * 银联支付
	 *
	 * @param string $name
	 * @param array $options
	 * @return Douyin
	 */
	public function douyin($name = null, array $options = []);

	/**
	 * 是否配置银联支付
	 *
	 * @return bool
	 */
	public function hasDouyin($name = null);

}
