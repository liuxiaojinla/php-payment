# Payment | 统一化支付

## 介绍
你还在为微信支付或支付宝支付编写两套不同逻辑的代码而头疼吗？你还在为庞杂的参数记忆而苦恼吗？

> 让我们回归本源，重新定义统一支付器。


## 安装教程

`composer require xin/payment`


## 概述

这是一个基于 `yansongda/pay` 库的支付 SDK，提供了统一的接口来处理微信支付、支付宝、银联支付和抖音支付。SDK 支持 Laravel 和 ThinkPHP 框架。

## 核心组件

### PaymentManager 类

`PaymentManager` 是主要的支付管理类，实现了 `PaymentFactory` 接口：

- **作用**: 统一管理所有支付方式的实例化和配置
- **继承**: 使用了四个 trait (`HasWechat`, `HasAlipay`, `HasUnipay`, `HasDouyin`)
- **依赖**: `WithConfig` trait 提供配置管理功能

### Factory 接口

定义了支付方式的统一接口：

- `wechat($name = null, array $options = [])` - 微信支付
- `alipay($name = null, array $options = [])` - 支付宝支付
- `unipay($name = null, array $options = [])` - 银联支付
- `douyin($name = null, array $options = [])` - 抖音支付
- 对应的 `hasWechat()`, `hasAlipay()`, `hasUnipay()`, `hasDouyin()` 方法用于检查配置

### PaymentType 常量类

定义了支付类型的常量：
- `WECHAT` - 微信支付
- `ALIPAY` - 支付宝支付
- `UNIPAY` - 银联支付
- `DOUYIN` - 抖音支付

## 支付方式实现

### 微信支付 (HasWechat Trait)

- **默认配置名**: 从 `defaults.wechat` 获取，默认为 'default'
- **配置路径**: `wechat.{name}`
- **特殊处理**:
    - 自动处理 `appid` 到 `app_id` 和 `miniapp_id` 的映射
    - 支持证书内容自动写入临时文件

### 支付宝 (HasAlipay Trait)

- **默认配置名**: 从 `defaults.alipay` 获取，默认为 'default'
- **配置路径**: `alipay.{name}`

### 银联支付 (HasUnipay Trait)

- **默认配置名**: 从 `defaults.unipay` 获取，默认为 'default'
- **配置路径**: `unipay.{name}`

### 抖音支付 (HasDouyin Trait)

- **默认配置名**: 从 `defaults.douyin` 获取，默认为 'default'
- **配置路径**: `douyin.{name}`

## 异常处理

### PaymentNotConfigureException

- 当支付配置未定义时抛出
- 继承自 `LogicException`

### PaymentInvalidConfigException

- 当支付配置无效时抛出
- 继承自 `LogicException`

## 框架集成

### Laravel 集成

- **服务提供者**: `Laravel\PaymentServiceProvider`
- **服务别名**:
    - `payment` - 主要服务
    - `payment.wechat`, `payment.alipay`, `payment.unipay`, `payment.douyin` - 各支付方式的快捷访问

### ThinkPHP 集成

- **服务类**: `ThinkPHP\PaymentServiceProvider`
- **绑定关系**: 将 `PaymentFactory` 和 `PaymentManager` 绑定到容器
- **服务别名**: 同 Laravel

## 配置结构

```php
// 配置示例
[
    'defaults' => [
        'wechat' => 'default',
        'alipay' => 'default', 
        'unipay' => 'default',
        'douyin' => 'default'
    ],
    'wechat' => [
        'default' => [
            // 微信支付配置
        ]
    ],
    'alipay' => [
        'default' => [
            // 支付宝配置
        ]
    ],
    'unipay' => [
        'default' => [
            // 银联支付配置
        ]
    ],
    'douyin' => [
        'default' => [
            // 抖音支付配置
        ]
    ],
    'logger' => [
        // 日志配置
    ],
    'http' => [
        // HTTP 配置
    ]
]
```


## 使用方法

### 基本使用

```php
// 获取支付实例
$wechat = $payment->wechat('default');
$alipay = $payment->alipay('default');
$unipay = $payment->unipay('default');
$douyin = $payment->douyin('default');

// 检查是否配置了支付方式
if ($payment->hasWechat('default')) {
    // 微信支付已配置
}
```


### 多配置支持

```php
// 使用不同配置名称
$wechat1 = $payment->wechat('shop1');
$wechat2 = $payment->wechat('shop2');
```


### 选项参数

```php
// 传递额外选项
$wechat = $payment->wechat('default', [
    'cert' => true  // 启用证书功能
]);
```


## 特殊功能

### 配置初始化

- 所有支付方式都支持通用配置初始化 (`initApplicationConfig`)
- 自动合并日志和 HTTP 配置

### 证书处理

- 微信支付支持证书内容自动写入临时文件
- 通过 `cert_client_content` 和 `cert_key_content` 配置项

### 配置验证

- 每种支付方式都有相应的 `has` 方法验证配置完整性
- 微信支付特别检查 `mch_id` 和 `key` 是否存在

## 目录结构

```
src/
├── Contracts/          # 接口定义
├── Exceptions/         # 异常类
├── Laravel/            # Laravel 集成
├── ThinkPHP/           # ThinkPHP 集成
├── HasAlipay.php       # 支付宝实现
├── HasDouyin.php       # 抖音支付实现
├── HasUnipay.php       # 银联支付实现
├── HasWechat.php       # 微信支付实现
├── PaymentManager.php  # 支付管理器
└── PaymentType.php     # 支付类型常量
```


这个 SDK 提供了一个统一、灵活且易于扩展的支付接口，支持主流的支付方式并集成了常用的 PHP 框架。

更多文档请参考【[easypay文档](https://pay.yansongda.cn/docs/v2/)】
