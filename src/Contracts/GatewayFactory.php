<?php

namespace Xin\Payment\Contracts;

interface GatewayFactory
{
	/**
	 * @param string $type
	 * @return Gateway
	 */
	public function pay($type = null);
}