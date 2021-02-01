<?php
/**
 * Talents come from diligence, and knowledge is gained by accumulation.
 *
 * @author: 晋<657306123@qq.com>
 */

namespace Xin\Payment\Contracts;

interface NotifyResult{
	
	/**
	 * 发送结果
	 *
	 * @param string|null $errMsg
	 * @return void
	 */
	public function send($errMsg = null);
	
	/**
	 * 获取结果
	 *
	 * @param string|null $errMsg
	 * @return string
	 */
	public function get($errMsg = null);
}
