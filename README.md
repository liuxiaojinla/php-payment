# Payment | 统一化支付

#### 介绍
你还在为微信支付或支付宝支付编写两套不同逻辑的代码而头疼吗？你还在为庞杂的参数记忆而苦恼吗？

让我们回归本源，重新定义统一支付器。


#### 软件架构
基于 EasyPay 使用工厂模式进行统一管理不同，使用者无需知道工厂类是如何构建一个支付驱动实例；
让我们更多的去关心业务层的输入/输出，框架本身实现了一套参数转换器，使用者只需调用统一输入/输出的参数即可；

#### 安装教程

`composer require xin/payment`


#### 使用说明
**配置文件**
```php
<?php
// +----------------------------------------------------------------------
// | 支付设置
// +----------------------------------------------------------------------

return [
	'defaults' => [
		// 微信支付默认配置
		'wechat' => 'default',

		// 支付宝默认配置
		'alipay' => 'default',

		/*
		 * 日志配置
		 *
		 * level: 日志级别，可选为：debug/info/notice/warning/error/critical/alert/emergency
		 * file：日志文件位置(绝对路径!!!)，要求可写权限
		 */
		'log' => [ // optional
			'enable' => false,
			'file' => runtime_path('logs') . 'payment.log',
			'level' => env('payment.log_level', env('app_env') !== 'production' ? 'debug' : 'info'), // 建议生产环境等级调整为 info，开发环境为 debug
			'type' => 'single', // optional, 可选 daily.
			'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
		],
		'http' => [ // optional
			'timeout' => 5.0,
			'connect_timeout' => 5.0,
			// 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
		],
	],

	// 微信支付配置
	'wechat' => [
		'default' => [
			'app_id' => env('wechat_pay.appid', ''),
			'mch_id' => env('wechat_pay.mch_id', ''),
			'key' => env('wechat_pay.key'),
			'cert_client' => env('wechat_pay.cert_client_path'),
			'cert_key' => env('wechat_pay.cert_key_path'),
		],
	],

	// 支付宝配置
	'alipay' => [
		'default' => [
			'app_id' => env('alipay.app_id', ''),
			'ali_public_key' => env('alipay.ali_public_key', ''),
			'private_key' => env('alipay.private_key', ''),// 加密方式： **RSA2**
			// 使用公钥证书模式，请配置下面两个参数，同时修改ali_public_key为以.crt结尾的支付宝公钥证书路径，如（./cert/alipayCertPublicKey_RSA2.crt）
			'app_cert_public_key' => env('alipay.app_cert_public_key', ''), //应用公钥证书路径
			'alipay_root_cert' => env('alipay.alipay_root_cert', ''), //支付宝根证书路径
			'aes_key' => env('alipay.aes_key', ''),
		],
	],
];
```

**构建统一化支付器**

```php
$paymentManager = new \Xin\Payment\PaymentManager();

// 微信支付
$paymentManager->wechat()->miniapp([
 // ...
]);

// 支付宝支付
$paymentManager->wechat()->miniapp([
 // ...
]);
```
更多文档请参考【[easypay文档](https://pay.yansongda.cn/docs/v2/)】