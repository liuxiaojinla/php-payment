<?php
/**
 * Talents come from diligence, and knowledge is gained by accumulation.
 *
 * @author: 晋<657306123@qq.com>
 */

namespace Xin\Payment\Contracts;

interface NotifyConverter{

	/**
	 * 转化数据
	 *
	 * @param array $result
	 * @return array
	 */
	public function transform($result);
}
