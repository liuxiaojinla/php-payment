<?php
/**
 * Talents come from diligence, and knowledge is gained by accumulation.
 *
 * @author: 晋<657306123@qq.com>
 */

namespace Xin\Payment\Kernel\Contracts;

use ArrayAccess;

/**
 * Interface Arrayable.
 */
interface Arrayable extends ArrayAccess{
	
	/**
	 * Get the instance as an array.
	 *
	 * @return array
	 */
	public function toArray();
}
