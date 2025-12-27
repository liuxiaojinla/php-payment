<?php
namespace Xin\Payment\ThinkPHP;

use think\Service;
use Xin\Payment\Contracts\Factory as PaymentFactory;
use Xin\Payment\PaymentManager;

class PaymentServiceProvider extends Service
{

	/**
	 * 启动器
	 */
	public function register()
	{
		$this->app->bind([
			'payment' => PaymentFactory::class,
			PaymentFactory::class => PaymentManager::class,
			PaymentManager::class => function () {
				return new PaymentManager(
					$this->app->config->get('payment')
				);
			},
		]);
	}

}
