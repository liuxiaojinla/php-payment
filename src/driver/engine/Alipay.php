<?php
/**
 * The following code, none of which has BUG.
 *
 * @author: BD<liuxingwu@duoguan.com>
 * @date: 2019/6/22 14:55
 */

namespace xin\payment\driver\engine;

use Exception;
use xin\payment\CloseOrderOptions;
use xin\payment\CloseOrderResult;
use xin\payment\OrderQueryOptions;
use xin\payment\OrderQueryResult;
use xin\payment\Payment;
use xin\payment\PaymentException;
use xin\payment\PaymentResult;
use xin\payment\RefundOptions;
use xin\payment\RefundQueryOptions;
use xin\payment\RefundQueryResult;
use xin\payment\RefundResult;
use xin\payment\ReverseOptions;
use xin\payment\ReverseResult;
use xin\payment\UnifiedOrderOptions;
use xin\payment\UnifiedOrderResult;

class Alipay extends Payment{

	//网关
	const gatewayUrl = "https://openapi.alipay.com/gateway.do";

	/**
	 * WeChat constructor.
	 *
	 * @param array $config
	 * @throws PaymentException
	 */
	public function __construct(array $config){
		// app_id 必填
		if(!isset($config['appid']) || empty($config['appid'])){
			throw new PaymentException('缺少必填参数 appid');
		}

		// mch_id 必填
		if(!isset($config['mch_id']) || empty($config['mch_id'])){
			throw new PaymentException('缺少必填参数 mch_id');
		}
		// key 必填
		if(!isset($config['key']) || empty($config['key'])){
			throw new PaymentException('缺少必填参数 key');
		}

		parent::__construct(array_merge([
			"version"    => "1.0",
			"sign_type"  => "RSA",
			"alipay_sdk" => "alipay-sdk-php-20180705",
			"charset"    => "UTF-8",
		], $config, [
			"format" => "json",
		]));
	}

	/**
	 * 统一下单
	 *
	 * @param UnifiedOrderOptions $input
	 * @return UnifiedOrderResult
	 * @throws PaymentException
	 */
	public function unifiedOrder(UnifiedOrderOptions $input){
		// TODO: Implement unifiedOrder() method.
	}

	/**
	 * 查询订单
	 *
	 * @param OrderQueryOptions $input
	 * @return OrderQueryResult
	 * @throws PaymentException
	 */
	public function orderQuery(OrderQueryOptions $input){
		// TODO: Implement orderQuery() method.
	}

	/**
	 * 关闭订单
	 *
	 * @param CloseOrderOptions $input
	 * @return CloseOrderResult
	 * @throws PaymentException
	 */
	public function closeOrder(CloseOrderOptions $input){
		// TODO: Implement closeOrder() method.
	}

	/**
	 * 申请退款
	 *
	 * @param RefundOptions $input
	 * @return RefundResult
	 * @throws PaymentException
	 */
	public function refund(RefundOptions $input){
		// TODO: Implement refund() method.
	}

	/**
	 * 退款查询
	 *
	 * @param RefundQueryOptions $input
	 * @return RefundQueryResult
	 * @throws PaymentException
	 */
	public function refundQuery(RefundQueryOptions $input){
		// TODO: Implement refundQuery() method.
	}

	/**
	 * 撤销订单
	 *
	 * @param ReverseOptions $input
	 * @return ReverseResult
	 * @throws PaymentException
	 */
	public function reverse(ReverseOptions $input){
		// TODO: Implement reverse() method.
	}

	/**
	 * 验证签名
	 *
	 * @param PaymentResult $result
	 * @throws PaymentException
	 */
	public function checkSign(PaymentResult $result){
		// TODO: Implement checkSign() method.
	}

	protected function request($options){
		$options = array_merge([
			"timestamp"     => date("Y-m-d H:i:s"),
			"terminal_type" => null,
			"terminal_info" => null,
			"prod_code"     => null,
			"notify_url"    => null,
		], $options, $this->buildBaseParams());
		$options['sign'] = $this->generateSign($options, $this->config['sign_type']);

		//系统参数放入GET请求串
		$requestUrl = $this->gatewayUrl."?";
		foreach($sysParams as $sysParamKey => $sysParamValue){
			$requestUrl .= "$sysParamKey=".urlencode($this->characet($sysParamValue, $this->postCharset))."&";
		}
		$requestUrl = substr($requestUrl, 0, -1);

		//发起HTTP请求
		try{
			$resp = $this->curl($requestUrl, $apiParams);
		}catch(Exception $e){
			$this->logCommunicationError($sysParams["method"], $requestUrl, "HTTP_ERROR_".$e->getCode(), $e->getMessage());
			return false;
		}

		//解析AOP返回结果
		$respWellFormed = false;

		// 将返回结果转换本地文件编码
		$r = iconv($this->postCharset, $this->fileCharset."//IGNORE", $resp);

		$signData = null;

		if("json" == $this->format){
			$respObject = json_decode($r);
			if(null !== $respObject){
				$respWellFormed = true;
				$signData = $this->parserJSONSignData($request, $resp, $respObject);
			}
		}elseif("xml" == $this->format){
			$disableLibxmlEntityLoader = libxml_disable_entity_loader(true);
			$respObject = @ simplexml_load_string($resp);
			if(false !== $respObject){
				$respWellFormed = true;

				$signData = $this->parserXMLSignData($request, $resp);
			}
			libxml_disable_entity_loader($disableLibxmlEntityLoader);
		}

		//返回的HTTP文本不是标准JSON或者XML，记下错误日志
		if(false === $respWellFormed){
			$this->logCommunicationError($sysParams["method"], $requestUrl, "HTTP_RESPONSE_NOT_WELL_FORMED", $resp);
			return false;
		}

		// 验签
		$this->checkResponseSign($request, $signData, $resp, $respObject);

		// 解密
		if(method_exists($request, "getNeedEncrypt") && $request->getNeedEncrypt()){
			if("json" == $this->format){
				$resp = $this->encryptJSONSignSource($request, $resp);

				// 将返回结果转换本地文件编码
				$r = iconv($this->postCharset, $this->fileCharset."//IGNORE", $resp);
				$respObject = json_decode($r);
			}else{
				$resp = $this->encryptXMLSignSource($request, $resp);

				$r = iconv($this->postCharset, $this->fileCharset."//IGNORE", $resp);
				$disableLibxmlEntityLoader = libxml_disable_entity_loader(true);
				$respObject = @ simplexml_load_string($r);
				libxml_disable_entity_loader($disableLibxmlEntityLoader);
			}
		}

		return $respObject;
	}

	/**
	 * 编译基础参数
	 *
	 * @return array
	 */
	protected function &buildBaseParams(){
		return [
			"version"    => $this->config["version"],
			"sign_type"  => $this->config["sign_type"],
			"alipay_sdk" => $this->config["alipay_sdk"],
			"charset"    => $this->config["charset"],
		];
	}

	/**
	 * 生成签名字符串
	 *
	 * @param array  $params
	 * @param string $signType
	 * @return mixed
	 */
	protected function generateSign($params, $signType = "RSA"){
		return $this->sign($this->getSignContent($params), $signType);
	}

	/**
	 * 获取前面字符串
	 *
	 * @param array $params
	 * @return string
	 */
	protected function getSignContent($params){
		ksort($params);

		$stringToBeSigned = "";
		$i = 0;
		foreach($params as $k => $v){
			if(false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)){
				// 转换成目标字符集
				$v = $this->characet($v, $this->config['charset']);

				if($i == 0){
					$stringToBeSigned .= "$k"."="."$v";
				}else{
					$stringToBeSigned .= "&"."$k"."="."$v";
				}
				$i++;
			}
		}

		unset ($k, $v);
		return $stringToBeSigned;
	}

	/**
	 * 校验$value是否非空
	 *  if not set ,return true;
	 *  if is null , return true;
	 *
	 * @param mixed $value
	 * @return bool
	 */
	protected function checkEmpty($value){
		if(!isset($value))
			return true;
		if($value === null)
			return true;
		if(trim($value) === "")
			return true;

		return false;
	}

	/** rsaCheckV1 & rsaCheckV2
	 *  验证签名
	 *  在使用本方法前，必须初始化AopClient且传入公钥参数。
	 *  公钥是否是读取字符串还是读取文件，是根据初始化传入的值判断的。
	 *
	 * @param array  $params
	 * @param string $rsaPublicKeyFilePath
	 * @param string $signType
	 * @return bool
	 */
	protected function rsaCheckV1($params, $rsaPublicKeyFilePath, $signType = 'RSA'){
		$sign = $params['sign'];
		$params['sign_type'] = null;
		$params['sign'] = null;
		return $this->verify($this->getSignContent($params), $sign, $rsaPublicKeyFilePath, $signType);
	}

	/**
	 * 验证签名
	 *
	 * @param array  $params
	 * @param string $rsaPublicKeyFilePath
	 * @param string $signType
	 * @return bool
	 */
	protected function rsaCheckV2($params, $rsaPublicKeyFilePath, $signType = 'RSA'){
		$sign = $params['sign'];
		$params['sign'] = null;
		return $this->verify($this->getSignContent($params), $sign, $rsaPublicKeyFilePath, $signType);
	}

	/**
	 * 转换字符集编码
	 *
	 * @param string $data
	 * @param string $targetCharset
	 * @return string
	 */
	public function characet($data, $targetCharset){
		if(!empty($data)){
			$fileType = "UTF-8";
			if(strcasecmp($fileType, $targetCharset) != 0){
				$data = mb_convert_encoding($data, $targetCharset, $fileType);
			}
		}

		return $data;
	}

	/**
	 * 验证数据
	 *
	 * @param string $data
	 * @param string $sign
	 * @param string $rsaPublicKeyFilePath
	 * @param string $signType
	 * @return bool
	 */
	public function verify($data, $sign, $rsaPublicKeyFilePath, $signType = 'RSA'){
		if($this->checkEmpty($this->alipayPublicKey)){
			$pubKey = $this->alipayrsaPublicKey;
			$res = "-----BEGIN PUBLIC KEY-----\n".
				   wordwrap($pubKey, 64, "\n", true).
				   "\n-----END PUBLIC KEY-----";
		}else{
			//读取公钥文件
			$pubKey = file_get_contents($rsaPublicKeyFilePath);
			//转换为openssl格式密钥
			$res = openssl_get_publickey($pubKey);
		}

		($res) or die('支付宝RSA公钥错误。请检查公钥文件格式是否正确');

		//调用openssl内置方法验签，返回bool值

		$result = false;
		if("RSA2" == $signType){
			$result = (openssl_verify($data, base64_decode($sign), $res, OPENSSL_ALGO_SHA256) === 1);
		}else{
			$result = (openssl_verify($data, base64_decode($sign), $res) === 1);
		}

		if(!$this->checkEmpty($this->alipayPublicKey)){
			//释放资源
			openssl_free_key($res);
		}

		return $result;
	}

	protected function sign($data, $signType = "RSA"){
		if($this->checkEmpty($this->rsaPrivateKeyFilePath)){
			$priKey = $this->rsaPrivateKey;
			$res = "-----BEGIN RSA PRIVATE KEY-----\n".
				   wordwrap($priKey, 64, "\n", true).
				   "\n-----END RSA PRIVATE KEY-----";
		}else{
			$priKey = file_get_contents($this->rsaPrivateKeyFilePath);
			$res = openssl_get_privatekey($priKey);
		}

		($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');

		if("RSA2" == $signType){
			openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
		}else{
			openssl_sign($data, $sign, $res);
		}

		if(!$this->checkEmpty($this->rsaPrivateKeyFilePath)){
			openssl_free_key($res);
		}
		$sign = base64_encode($sign);
		return $sign;
	}

}
