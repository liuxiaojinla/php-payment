<?php
/**
 * The following code, none of which has BUG.
 *
 * @author: BD<657306123@qq.com>
 * @date: 2019/11/26 15:09
 */

namespace xin\payment;

interface ConfigInterface{

	/**
	 * 返回 微信支付 Appid
	 *
	 * @return string
	 */
	public function getWxPayAppId();

	/**
	 * 返回 微信支付 mch_id
	 *
	 * @return string
	 */
	public function getWxPayMchId();

	/**
	 * 返回微信支付 支付密钥
	 *
	 * @return string
	 */
	public function getWxPayKey();

	/**
	 * 返回微信支付 安全证书
	 *
	 * @return array
	 */
	public function getWxPaySslCertPath();
}
