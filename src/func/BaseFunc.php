<?php
/**
 * The following code, none of which has BUG.
 *
 * @author: BD<657306123@qq.com>
 * @date: 2019/11/26 14:25
 */

namespace xin\payment\func;

use xin\payment\BindFuncInterface;
use xin\payment\ConfigInterface;
use xin\payment\entity\PaymentInput;
use xin\payment\PayType;
use xin\payment\ProviderInterface;

/**
 * Class BaseFunc
 *
 * @package xin\payment\func
 */
abstract class BaseFunc implements ProviderInterface{

	/**
	 * @var array
	 */
	private $config;

	/**
	 * 绑定操作
	 *
	 * @param \xin\payment\BindFuncInterface $func
	 * @param \xin\payment\ConfigInterface   $config
	 */
	public function bindCall(BindFuncInterface $func, ConfigInterface $config){
		$this->config = $config;

		$classname = get_class($this);
		$classname = basename($classname);
		$classname = lcfirst($classname);

		$func->bind($classname, function(ConfigInterface $config, PaymentInput $input){
			if(PayType::ALIPAY == $input->getPayType()){
				return $this->onAlipay($config, $input);
			}else{
				return $this->onWxPay($config, $input);
			}
		});
	}

	/**
	 * 支付宝
	 *
	 * @param \xin\payment\ConfigInterface     $config
	 * @param \xin\payment\entity\PaymentInput $input
	 * @return mixed
	 */
	abstract protected function onAliPay(ConfigInterface $config, PaymentInput $input);

	/**
	 * 微信
	 *
	 * @param \xin\payment\ConfigInterface     $config
	 * @param \xin\payment\entity\PaymentInput $input
	 * @return mixed
	 */
	abstract protected function onWxPay(ConfigInterface $config, PaymentInput $input);
}
