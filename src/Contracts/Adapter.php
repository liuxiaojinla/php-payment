<?php
/**
 * Talents come from diligence, and knowledge is gained by accumulation.
 *
 * @author: 晋<657306123@qq.com>
 */

namespace Xin\Payment\Contracts;

use Xin\Payment\Bus\Input;

interface Adapter{
	
	/**
	 * 适配器
	 *
	 * @param string                 $action
	 * @param \Xin\Payment\Bus\Input $input
	 * @param array                  ...$arguments
	 * @return mixed
	 */
	public function execute($action, Input $input, ...$arguments);
	
	/**
	 * 使用一个网关
	 *
	 * @param string $gateway
	 */
	public function shouldUse($gateway);
	
	/**
	 * 使用一个微信支付网关
	 */
	public function shouldUseWechat();
	
	/**
	 * 使用一个支付宝支付网关
	 */
	public function shouldUseAlipay();
	
	/**
	 * 验证回调数据
	 *
	 * @param bool $needDecrypt 是否需要对数据进行解密
	 * @return array
	 */
	public function notify($needDecrypt = false);
}
