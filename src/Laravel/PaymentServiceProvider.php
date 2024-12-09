<?php

namespace Xin\Payment\Laravel;

use Illuminate\Support\ServiceProvider;
use Xin\Payment\Contracts\Factory as PaymentFactory;
use Xin\Payment\PaymentManager;

class PaymentServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerManager();

		$this->registerCommands();

		$this->mergeConfigFrom(__DIR__ . '/../../config/config.php', 'payment');
	}

	/**
	 * Booting the package.
	 */
	public function boot()
	{
		$this->registerConfig();
	}

	/**
	 * 注册管理器
	 * @return void
	 */
	protected function registerManager()
	{
		$this->app->singleton(PaymentManager::class, function () {
			$manager = new PaymentManager(
				$this->app['config']->get('payment'),
			);

			$this->registerDrivers($manager);

			return $manager;
		});
		$this->app->alias(PaymentManager::class, 'payment');
		$this->app->alias(PaymentManager::class, PaymentFactory::class);

		$this->app->singleton('payment.wechat', function ($app) {
			return $app['payment']->wechat();
		});

		$this->app->singleton('payment.alipay', function ($app) {
			return $app['payment']->alipay();
		});

		$this->app->singleton('payment.unipay', function ($app) {
			return $app['payment']->unipay();
		});

		$this->app->singleton('payment.douyin', function ($app) {
			return $app['payment']->douyin();
		});
	}

	/**
	 * 注册驱动
	 */
	protected function registerDrivers(PaymentManager $manager)
	{

	}

	protected function registerCommands()
	{
		$this->commands([
		]);
	}

	/**
	 * 注册配置
	 * @return void
	 */
	protected function registerConfig()
	{
		$configPath = __DIR__ . '/../../config/config.php';
		$this->publishes([
			$configPath => config_path('payment.php'),
		], 'config');
	}
}
