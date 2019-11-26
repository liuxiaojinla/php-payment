<?php
/**
 * The following code, none of which has BUG.
 *
 * @author: BD<657306123@qq.com>
 * @date: 2019/11/26 14:39
 */

namespace xin\payment;

interface ProviderInterface{

	/**
	 * 绑定操作
	 *
	 * @param \xin\payment\BindFuncInterface $func
	 * @param \xin\payment\ConfigInterface   $config
	 */
	public function bindCall(BindFuncInterface $func, ConfigInterface $config);
}
