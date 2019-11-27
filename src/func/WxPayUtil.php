<?php
/**
 * The following code, none of which has BUG.
 *
 * @author: BD<657306123@qq.com>
 * @date: 2019/11/26 15:40
 */

namespace xin\payment\func;

use xin\payment\ConfigInterface;
use xin\payment\entity\PaymentInput;
use xin\payment\entity\PaymentOutput;
use xin\payment\PaymentException;
use xin\payment\PayType;
use xin\payment\Util;

/**
 * Class WechatUtil
 *
 * @package xin\payment\func
 */
class WxPayUtil{

	/**
	 * SDK版本号
	 *
	 * @var string
	 */
	public static $VERSION = "3.0.10";

	/**
	 * 请求
	 *
	 * @param string $url
	 * @param mixed  $data
	 * @param array  $options
	 * @return string
	 * @throws \xin\payment\func\WxPayException
	 */
	public static function request($url, $data, array $options = []){
		$ch = curl_init();
		$curlVersion = curl_version();
		$ua = "WXPaySDK/".self::$VERSION." (".PHP_OS.") PHP/".PHP_VERSION." CURL/".$curlVersion['version'];

		//设置超时
		$timeout = isset($options['timeout']) ? $options['timeout'] : 6;
		if($timeout){
			curl_setopt($ch, CURLOPT_TIMEOUT, $options['timeout']);
		}

		//如果有配置代理这里就设置代理
		$proxyHost = isset($options['proxy_host']) ? $options['proxy_host'] : "0.0.0.0";
		$proxyPort = isset($options['proxy_port']) ? $options['proxy_port'] : 0;
		if($proxyHost != "0.0.0.0" && $proxyPort != 0){
			curl_setopt($ch, CURLOPT_PROXY, $proxyHost);
			curl_setopt($ch, CURLOPT_PROXYPORT, $proxyPort);
		}

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);//严格校验
		curl_setopt($ch, CURLOPT_USERAGENT, $ua);

		//设置header
		curl_setopt($ch, CURLOPT_HEADER, false);

		//要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		//设置证书
		//使用证书：cert 与 key 分别属于两个.pem文件
		//证书文件请放入服务器的非web目录下
		$sslCertPath = isset($options['ssl_cert_path']) ? $options['ssl_cert_path'] : "";
		$sslKeyPath = isset($options['ssl_key_path']) ? $options['ssl_key_path'] : "";
		if($sslCertPath && $sslKeyPath){
			curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
			curl_setopt($ch, CURLOPT_SSLCERT, $sslCertPath);
			curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
			curl_setopt($ch, CURLOPT_SSLKEY, $sslKeyPath);
		}

		//post提交方式
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		//运行curl
		$data = curl_exec($ch);

		//返回结果
		if(!$data){
			$error = curl_errno($ch);
			curl_close($ch);
			throw new WxPayException("curl出错，错误码:$error");
		}

		curl_close($ch);
		return $data;
	}

	/**
	 * 初始化 请求参数
	 *
	 * @param \xin\payment\ConfigInterface     $config
	 * @param \xin\payment\entity\PaymentInput $input
	 * @throws \xin\payment\PaymentException
	 */
	public static function initWxPayInput(ConfigInterface $config, PaymentInput $input){
		$data = [
			'appid'  => $config->getWxPayAppId(),
			'mch_id' => $config->getWxPayMchId(),
			'key'    => $config->getWxPayKey(),
		];

		// app_id 必填
		if(!isset($data['appid']) || empty($data['appid'])){
			throw new PaymentException('缺少必填参数 appid');
		}

		// mch_id 必填
		if(!isset($data['mch_id']) || empty($data['mch_id'])){
			throw new PaymentException('缺少必填参数 mch_id');
		}

		// key 必填
		if(!isset($data['key']) || empty($data['key'])){
			throw new PaymentException('缺少必填参数 key');
		}

		$input->set('appid', $data['appid']);//公众账号ID
		$input->set('mch_id', $data['mch_id']);//商户号
		$input->set('nonce_str', Util::nonceStr());//随机字符串

		$input->remove(PayType::__NAME__);
	}

	/**
	 * 初始化 请求结果
	 *
	 * @param string          $response
	 * @param string          $class
	 * @param ConfigInterface $config
	 * @return mixed
	 * @throws \xin\payment\PaymentException
	 * @throws \xin\payment\func\WxPayException
	 */
	public static function makeOutput($response, $class, ConfigInterface $config){
		/**@var $class PaymentOutput::class */
		$result = $class::fromXML($response, $config);

		if($result->get('return_code') != 'SUCCESS'){
			throw new PaymentException($result->get('return_msg'));
		}

		if($result->get('result_code') != 'SUCCESS'){
			throw new WxPayException($result->get('err_code_des'), $result->get('err_code'));
		}

		// 检测签名
		self::checkSign($result->toArray(), $config->getWxPayKey());

		return $result;
	}

	/**
	 * 检测签名
	 *
	 * @param array  $data
	 * @param string $key
	 * @throws \xin\payment\PaymentException
	 */
	public static function checkSign(array $data, $key){
		if(!isset($data['sign'])){
			throw new PaymentException("签名错误[sign not exist]！");
		}

		// 计算签名加密类型，如果签名小于等于32个,则使用md5验证，否则是用sha256校验
		$encryptType = strlen($data['sign']) <= 32 ? 0 : 1;
		$sign = self::makeSign($data, $key, $encryptType);

		if($data['sign'] != $sign){
			throw new PaymentException("签名错误！");
		}
	}

	/**
	 * 数据签名
	 *
	 * @param array  $data
	 * @param string $key
	 * @param int    $encryptType
	 * @return string
	 */
	public static function makeSign(array $data, $key, $encryptType = 0){
		//签名步骤一：按字典序排序参数
		ksort($data);
		$sign = Util::buildParamsToUrl($data);
		//签名步骤二：在string后加入KEY
		$sign = $sign."&key=".$key;

		//签名步骤三：MD5加密或者HMAC-SHA256
		if($encryptType == 0){
			$sign = md5($sign);
		}else{
			$sign = hash_hmac("sha256", $sign, $key);
		}

		//签名步骤四：所有字符转为大写
		$sign = strtoupper($sign);
		return $sign;
	}
}
