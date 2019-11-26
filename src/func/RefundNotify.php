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
use xin\payment\ProviderInterface;

class RefundNotify implements ProviderInterface{

	/**
	 * 绑定操作
	 *
	 * @param \xin\payment\BindFuncInterface $func
	 * @param \xin\payment\ConfigInterface   $config
	 */
	public function bindCall(BindFuncInterface $func, ConfigInterface $config){
		// TODO: Implement bindCall() method.
	}
}
