# yii2-chat

IM组件

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
$ composer require yuncms/yii2-im:~1.0
```

or add

```
"yuncms/yii2-im": "~1.0"
```

to the `require` section of your `composer.json` file.

##设置

后台

```php
	'live' => [
		'class' => 'yuncms\im\backend\Module',
	],
```

前台

```php
	'live' => [
		'class' => 'yuncms\im\frontend\Module',
	],
```

Api

```php
	'live' => [
		'class' => 'yuncms\im\api\Module',
	],
```

微信

```php
	'live' => [
		'class' => 'yuncms\im\wechat\Module',
	],
```

///TODO

实现群组

实现点对点

后台群推