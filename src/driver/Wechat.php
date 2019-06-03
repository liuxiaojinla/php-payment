<?php
/**
 * I know no such things as genius,it is nothing but labor and diligence.
 *
 * @copyright (c) 2015~2019 BD All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author BD<657306123@qq.com>
 */

namespace xin\payment\driver;

use xin\payment\CloseOrderOptions;
use xin\payment\CloseOrderResult;
use xin\payment\OrderQueryOptions;
use xin\payment\OrderQueryResult;
use xin\payment\Payment;
use xin\payment\PaymentException;
use xin\payment\PaymentOptions;
use xin\payment\PaymentResult;
use xin\payment\RefundOptions;
use xin\payment\RefundQueryOptions;
use xin\payment\RefundQueryResult;
use xin\payment\RefundResult;
use xin\payment\ReverseOptions;
use xin\payment\ReverseResult;
use xin\payment\UnifiedOrderOptions;
use xin\payment\UnifiedOrderResult;
use xin\payment\Util;

/**
 * 微信支付
 * PaymentOptions 不需要填入：appid、mchid、spbill_create_ip、nonce_str
 * @method string getSignKey() 获取sign_key
 * @method string getMchId() 获取微信商家ID
 * @method string getSslCert() 获取证书数据
 * @method void setSslCert(string $sslCert) 设置证书数据
 * @method string getSslKey() 获取证书密钥
 * @method void setSslKey(string $sslKey) 设置证书密钥
 *
 * @package xin\payment\driver
 */
class Wechat extends Payment{

	/**
	 * WeChat constructor.
	 *
	 * @param array $config
	 * @throws PaymentException
	 */
	public function __construct(array $config){
		// app_id 必填
		if(!isset($config['app_id']) || empty($config['app_id'])){
			throw new PaymentException('缺少必填参数 app_id');
		}

		// mch_id 必填
		if(!isset($config['mch_id']) || empty($config['mch_id'])){
			throw new PaymentException('缺少必填参数 mch_id');
		}
		// sign_key 必填
		if(!isset($config['sign_key']) || empty($config['sign_key'])){
			throw new PaymentException('缺少必填参数 sign_key');
		}

		parent::__construct($config);
	}

	/**
	 * 统一下单
	 * UnifiedOrderPaymentOptions 中必填：
	 * out_trade_no body 、 total_fee、 trade_type、
	 *
	 * @param UnifiedOrderOptions $input
	 * @return UnifiedOrderResult
	 * @throws PaymentException
	 */
	public function unifiedOrder(UnifiedOrderOptions $input){
		$input->check([
			'out_trade_no', 'body',
			'total_fee', 'trade_type',
		]);

		if($input->getTradeType() == "JSAPI" && !$input->has('openid')){
			throw new PaymentException("统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！");
		}

		if($input->getTradeType() == "NATIVE" && !$input->has('product_id')){
			throw new PaymentException("统一支付接口中，缺少必填参数product_id！trade_type为JSAPI时，product_id为必填参数！");
		}

		// 转换keys
		$input = $input->transformKeys([
			'start_time'  => 'time_start',
			'expire_time' => 'time_expire',
		]);
		//终端ip
		$input->set('spbill_create_ip', isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');

		$url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
		/**@var $result UnifiedOrderResult */
		$result = $this->result($url, $input, UnifiedOrderResult::class, false, 6);

		return $result->transformKeys([

		]);
	}

	/**
	 * 获取结果
	 *
	 * @param string         $url
	 * @param PaymentOptions $input
	 * @param string         $paymentResultClass
	 * @param bool           $useCert
	 * @param int            $second
	 * @return mixed
	 * @throws PaymentException
	 */
	protected function result($url, PaymentOptions $input, $paymentResultClass, $useCert = false, $second = 30){
		//请求开始时间
		$startTimeStamp = Util::getMillisecond();

		// 请求数据
		$xml = $this->initOptions($input)->toXml();
		$response = self::request($url, $xml, $useCert, $second);

		/**@var $paymentResultClass PaymentResult::class */
		$result = $paymentResultClass::fromXML($response);
		$result = $this->initResult($result);

		self::reportCostTime($url, $startTimeStamp, $result);//上报请求花费时间

		return $result;
	}

	/**
	 * 初始化Input实例
	 *
	 * @param PaymentOptions $input
	 * @return PaymentOptions
	 */
	protected function initOptions(PaymentOptions $input){
		$input->set('appid', $this->config['appid']);//公众账号ID
		$input->set('mch_id', $this->config['mch_id']);//商户号
		$input->set('nonce_str', Util::nonceStr());//随机字符串
		$this->setSign($input);
		return $input;
	}

	/**
	 * 设置支付签名
	 *
	 * @param PaymentOptions $options
	 */
	public function setSign(PaymentOptions $options){
		$sign = self::makeSign($this->getSignKey(), $options->toArray());
		$options->setSign($sign);
	}

	/**
	 * 数据签名
	 *
	 * @param string $key
	 * @param array  $data
	 * @return string
	 */
	public static function makeSign($key, $data){
		//签名步骤一：按字典序排序参数
		ksort($data);
		$sign = Util::buildParamsToUrl($data);
		//签名步骤二：在string后加入KEY
		$sign = $sign."&key=".$key;
		//签名步骤三：MD5加密
		$sign = md5($sign);
		//签名步骤四：所有字符转为大写
		$sign = strtoupper($sign);
		return $sign;
	}

	/**
	 * 以post方式提交xml到对应的接口url
	 *
	 * @param string $url url
	 * @param string $xml 需要post的xml数据
	 * @param bool   $useCert 是否需要证书，默认不需要
	 * @param int    $second url执行超时时间，默认30s
	 * @return mixed
	 * @throws PaymentException
	 */
	protected function request($url, $xml, $useCert = false, $second = 30){
		$ch = curl_init();

		//设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, $second);

		//如果有配置代理这里就设置代理
		$proxyHost = $this->hasConfig('proxy_host');
		$proxyPort = $this->hasConfig('proxy_port');
		if($proxyHost
		   && $proxyHost != "0.0.0.0"
		   && $proxyPort
		   && $proxyPort != 0){
			curl_setopt($ch, CURLOPT_PROXY, $proxyHost);
			curl_setopt($ch, CURLOPT_PROXYPORT, $proxyPort);
		}
		curl_setopt($ch, CURLOPT_URL, $url);

		//设置header
		curl_setopt($ch, CURLOPT_HEADER, false);
		//要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		//设置证书
		if($useCert == true){
			//严格校验
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

			//使用证书：cert 与 key 分别属于两个.pem文件
			curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
			curl_setopt($ch, CURLOPT_SSLCERT, $this->getSslCert());
			curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
			curl_setopt($ch, CURLOPT_SSLKEY, $this->getSslKey());
		}else{
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		}

		//post提交方式
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

		//运行curl
		$data = curl_exec($ch);
		//返回结果
		if($data){
			curl_close($ch);
			return $data;
		}else{
			$errCode = curl_errno($ch);
			$errMsg = curl_error($ch);
			curl_close($ch);
			throw new PaymentException("curl error:{$errMsg}", $errCode);
		}
	}

	/**
	 * 初始化Output
	 *
	 * @param PaymentResult $result
	 * @return mixed
	 * @throws PaymentException
	 */
	protected function initResult(PaymentResult $result){
		if($result->get('return_code') != 'SUCCESS'){
			throw new PaymentException($result->get('return_msg'));
		}

		if($result->get('result_code') != 'SUCCESS'){
			throw new WechatPaymentException($result->get('err_code_des'), $result->get('err_code'));
		}

		$this->checkSign($result);

		return $result;
	}

	/**
	 * 检测签名
	 *
	 * @param PaymentResult $result
	 * @throws PaymentException
	 */
	public function checkSign(PaymentResult $result){
		if(!$result->hasSign()) throw new PaymentException("签名错误[sign not exist]！");

		$sign = self::makeSign($this->getSignKey(), $result->toArray());
		if($result->getSign() != $sign){
			throw new PaymentException("签名错误！");
		}
	}

	/**
	 * 上报数据， 上报的时候将屏蔽所有异常流程
	 *
	 * @param string              $url
	 * @param int                 $startTimeStamp
	 * @param array|PaymentResult $data
	 */
	protected function reportCostTime($url, $startTimeStamp, $data){
		$reportLevel = $this->getConfig('report_level', 0);

		//如果不需要上报数据
		if($reportLevel == 0){
			return;
		}

		//如果仅失败上报
		if($reportLevel == 1
		   && array_key_exists("return_code", $data)
		   && $data["return_code"] == "SUCCESS"
		   && array_key_exists("result_code", $data)
		   && $data["result_code"] == "SUCCESS"){
			return;
		}

		//上报逻辑
		$endTimeStamp = Util::getMillisecond();

		$input = new PaymentOptions();
		$input->set('interface_url', $url);
		$input->set('execute_time_', $endTimeStamp - $startTimeStamp);

		//返回状态码
		if(array_key_exists("return_code", $data)){
			$input->set('return_code', $data["return_code"]);
		}
		//返回信息
		if(array_key_exists("return_msg", $data)){
			$input->set('return_msg', $data["return_msg"]);
		}
		//业务结果
		if(array_key_exists("result_code", $data)){
			$input->set('result_code', $data["result_code"]);
		}
		//错误代码
		if(array_key_exists("err_code", $data)){
			$input->set('err_code', $data["err_code"]);
		}
		//错误代码描述
		if(array_key_exists("err_code_des", $data)){
			$input->set('err_code_des', $data["err_code_des"]);
		}
		//商户订单号
		if(array_key_exists("out_trade_no", $data)){
			$input->set('out_trade_no', $data["out_trade_no"]);
		}
		//设备号
		if(array_key_exists("device_info", $data)){
			$input->set('device_info', $data["device_info"]);
		}

		try{
			self::report($input);
		}catch(PaymentException $e){
			//不做任何处理
		}
	}

	/**
	 * 测速上报，该方法内部封装在report中，使用时请注意异常流程
	 * WxPayReport中interface_url、return_code、result_code、user_ip、execute_time_必填
	 * appid、mchid、spbill_create_ip、nonce_str不需要填入
	 *
	 * @param PaymentOptions $input
	 * @return mixed
	 * @throws PaymentException
	 */
	public function report(PaymentOptions $input){
		if(!isset($input->interface_url)){
			throw new PaymentException("接口URL，缺少必填参数interface_url！");
		}
		if(!isset($input->return_code)){
			throw new PaymentException("返回状态码，缺少必填参数return_code！");
		}
		if(!isset($input->result_code)){
			throw new PaymentException("业务结果，缺少必填参数result_code！");
		}
		if(!isset($input->execute_time_)){
			throw new PaymentException("接口耗时，缺少必填参数execute_time_！");
		}

		$input->set('user_ip', $_SERVER['REMOTE_ADDR']);//终端ip
		$input->set('time', date("YmdHis"));//商户上报时间

		$url = "https://api.mch.weixin.qq.com/payitil/report";
		$xml = $this->initOptions($input)->toXml();
		$response = self::request($url, $xml, false, 1);
		return $response;
	}

	/**
	 * 查询订单
	 * OrderQueryPaymentOptions 中 out_trade_no、transaction_id至少填一个
	 *
	 * @param OrderQueryOptions $input
	 * @return OrderQueryResult
	 * @throws PaymentException
	 */
	public function orderQuery(OrderQueryOptions $input){
		if(!$input->hasOutTradeNo() && !$input->hasTransactionId()){
			throw new PaymentException("订单查询接口中，out_trade_no、transaction_id至少填一个！");
		}

		$url = "https://api.mch.weixin.qq.com/pay/orderquery";
		$result = $this->result($url, $input, OrderQueryResult::class, false, 6);
		return $result->transformKeys([

		]);
	}

	/**
	 * 关闭订单，WxPayCloseOrder中out_trade_no必填
	 * appid、mchid、spbill_create_ip、nonce_str不需要填入
	 *
	 * @param CloseOrderOptions $input
	 * @return CloseOrderResult
	 * @throws PaymentException
	 */
	public function closeOrder(CloseOrderOptions $input){
		if(!$input->hasOutTradeNo()){
			throw new PaymentException("订单查询接口中，out_trade_no必填！");
		}

		$url = "https://api.mch.weixin.qq.com/pay/closeorder";
		$result = $this->result($url, $input, CloseOrderResult::class, false, 6);
		return $result->transformKeys([

		]);
	}

	/**
	 * 申请退款，WxPayRefund中out_trade_no、transaction_id至少填一个且
	 * out_refund_no、total_fee、refund_fee、op_user_id为必填参数
	 * appid、mchid、spbill_create_ip、nonce_str不需要填入
	 *
	 * @param RefundOptions $input
	 * @return RefundResult
	 * @throws PaymentException
	 */
	public function refund(RefundOptions $input){
		if(!$input->hasOutTradeNo() && !$input->hasTransactionId()){
			throw new PaymentException("退款申请接口中，out_trade_no、transaction_id至少填一个！");
		}elseif(!$input->hasOutRefundNo()){
			throw new PaymentException("退款申请接口中，缺少必填参数out_refund_no！");
		}elseif(!$input->hasTotalFee()){
			throw new PaymentException("退款申请接口中，缺少必填参数total_fee！");
		}elseif(!$input->hasRefundFee()){
			throw new PaymentException("退款申请接口中，缺少必填参数refund_fee！");
		}elseif(!$input->hasOpUserId()){
			throw new PaymentException("退款申请接口中，缺少必填参数op_user_id！");
		}

		$url = "https://api.mch.weixin.qq.com/secapi/pay/refund";
		$result = $this->result($url, $input, RefundResult::class, false, 6);
		return $result->transformKeys([

		]);
	}

	/**
	 * 查询退款
	 * 提交退款申请后，通过调用该接口查询退款状态。退款有一定延时，
	 * 用零钱支付的退款20分钟内到账，银行卡支付的退款3个工作日后重新查询退款状态。
	 * WxPayRefundQuery中out_refund_no、out_trade_no、transaction_id、refund_id四个参数必填一个
	 * appid、mchid、spbill_create_ip、nonce_str不需要填入
	 *
	 * @param RefundQueryOptions $input
	 * @return RefundQueryResult
	 * @throws PaymentException
	 */
	public function refundQuery(RefundQueryOptions $input){
		if(!$input->hasOutRefundNo()
		   && !$input->hasOutTradeNo()
		   && !$input->hasTransactionId()
		   && !$input->hasRefundId()){
			throw new PaymentException("退款查询接口中，out_refund_no、out_trade_no、transaction_id、refund_id四个参数必填一个！");
		}

		$url = "https://api.mch.weixin.qq.com/pay/refundquery";
		$result = $this->result($url, $input, RefundQueryResult::class, false, 6);
		return $result->transformKeys([

		]);
	}

	/**
	 * 撤销订单API接口，WxPayReverse中参数out_trade_no和transaction_id必须填写一个
	 * appid、mchid、spbill_create_ip、nonce_str不需要填入
	 *
	 * @param ReverseOptions $input
	 * @return ReverseResult
	 * @throws PaymentException
	 */
	public function reverse(ReverseOptions $input){
		if(!$input->hasOutTradeNo() && !$input->hasTransactionId()){
			throw new PaymentException("撤销订单API接口中，参数out_trade_no和transaction_id必须填写一个！");
		}

		$url = "https://api.mch.weixin.qq.com/secapi/pay/reverse";
		$result = $this->result($url, $input, ReverseResult::class, false, 6);
		return $result->transformKeys([

		]);
	}

	/**
	 * 支付结果通用通知
	 *
	 * @param string $msg
	 * @return bool|PaymentResult
	 */
	public static function notify(&$msg){
		//获取通知的数据
		$xml = file_get_contents('php://input');
		//如果返回成功则验证签名
		try{
			$result = PaymentResult::fromXML($xml);
			return $result;
		}catch(PaymentException $e){
			$msg = $e->getMessage();
			return false;
		}
	}

}
