<?php
namespace Xin\Payment\ThinkPHP;

use Xin\Contracts\Payment\Factory as PaymentFactory;
use Xin\Payment\PaymentManager;
use Xin\Thinkphp\Foundation\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
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
