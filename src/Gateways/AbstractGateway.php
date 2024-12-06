<?php

namespace Xin\Payment\Gateways;

use Xin\Payment\Contracts\Gateway as GatewayContract;
use Xin\Payment\GatewayManager;


/**
 * @deprecated
 */
abstract class AbstractGateway implements GatewayContract
{

	/**
	 * @var GatewayManager
	 */
	protected $payManager;

	/**
	 * @var GatewayApplicationInterface
	 */
	protected $gateway;

	/**
	 * 微信支付器
	 *
	 * @param GatewayManager $payManager
	 * @param GatewayApplicationInterface $gateway
	 */
	public function __construct(GatewayManager $payManager, GatewayApplicationInterface $gateway)
	{
		$this->payManager = $payManager;
		$this->gateway = $gateway;
	}

	/**
	 * 安全调用
	 * @param callable $callback
	 * @return \Yansongda\Supports\Collection
	 */
	protected function call(callable $callback)
	{
		try {
			return $callback();
		} catch (BusinessException $e) {
			throw new \LogicException($e->getMessage(), $e->getCode(), $e);
		} catch (GatewayException $e) {
			throw new \LogicException($e->getMessage(), $e->getCode(), $e);
		} catch (InvalidConfigException $e) {
			throw new \LogicException($e->getMessage(), $e->getCode(), $e);
		} catch (InvalidGatewayException $e) {
			throw new \LogicException($e->getMessage(), $e->getCode(), $e);
		} catch (InvalidSignException $e) {
			throw new \LogicException("签名错误，请检查支付配置", $e->getCode(), $e);
		}
	}

}
