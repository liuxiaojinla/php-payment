<?php
/**
 * Talents come from diligence, and knowledge is gained by accumulation.
 *
 * @author: 晋<657306123@qq.com>
 */

namespace Xin\Payment\Contracts;

use Xin\Payment\Bus\Input;

interface Adapter{

	/**
	 * 适配器
	 *
	 * @param string                 $action
	 * @param \Xin\Payment\Bus\Input $input
	 * @param array                  ...$arguments
	 * @return mixed
	 */
	public function execute($action, Input $input, ...$arguments);

	/**
	 * 验证回调数据
	 *
	 * @param string $channel
	 * @param bool   $needDecrypt 是否需要对数据进行解密
	 * @return array
	 */
	public function notify($channel, $needDecrypt = false);
}
