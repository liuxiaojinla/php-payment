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
	 * 注册站点配置管理器
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

		$this->app->singleton('menu.driver', function ($app) {
			return $app['menu']->menu();
		});
	}

	/**
	 * 注册菜单驱动
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

//		$this->replaceInFile(
//			"runtime_path('logs')",
//			"storage_path('logs')",
//			$configPath
//		);
	}

	/**
	 * Replace a given string within a given file.
	 *
	 * @param  string  $search
	 * @param  string  $replace
	 * @param  string  $path
	 * @return void
	 */
	protected function replaceInFile($search, $replace, $path)
	{
		file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
	}
}
