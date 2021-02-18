<?php
/**
 * Talents come from diligence, and knowledge is gained by accumulation.
 *
 * @author: 晋<657306123@qq.com>
 */

namespace Xin\Payment\Contracts;

interface NotifyResult{
	
	/**
	 * 返回成功信息
	 *
	 * @return string
	 */
	public function success();
	
	/**
	 * 返回失败消息
	 *
	 * @param string|null $errMsg
	 * @return string
	 */
	public function error($errMsg);
}
