<?php

namespace Xin\Payment\Contracts;

/**
 * @deprecated
 */
interface GatewayFactory
{
	/**
	 * @param string $type
	 * @return Gateway
	 */
	public function pay($type = null);
}
