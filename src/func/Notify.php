<?php
/**
 * The following code, none of which has BUG.
 *
 * @author: BD<657306123@qq.com>
 * @date: 2019/11/26 17:00
 */

namespace xin\payment\func;

use xin\payment\BindFuncInterface;
use xin\payment\ConfigInterface;
use xin\payment\entity\PaymentOutput;
use xin\payment\ProviderInterface;

class Notify implements ProviderInterface{

	/**
	 * 绑定操作
	 *
	 * @param \xin\payment\BindFuncInterface $func
	 * @param \xin\payment\ConfigInterface   $config
	 */
	public function bindCall(BindFuncInterface $func, ConfigInterface $config){
		// TODO: Implement bindCall() method.
	}

	/**
	 * 支付结果通用通知
	 *
	 * @param string $msg
	 * @return PaymentOutput
	 */
	public function onWxPay(&$msg){
		//获取通知的数据
		$xml = file_get_contents('php://input');

		//如果返回成功则验证签名
		try{
			$result = PaymentOutput::fromXML($xml, null);

			return $result;
		}catch(\Exception $e){
			$msg = $e;
		}

		return null;
	}
}
