<?php
/**
 * The following code, none of which has BUG.
 *
 * @author: BD<657306123@qq.com>
 * @date: 2019/11/26 14:26
 */

namespace xin\payment;

interface BindFuncInterface{

	/**
	 * 绑定函数
	 *
	 * @param string $name
	 * @param mixed  $call
	 */
	public function bind($name, $call);
}
