# Yii2 AdminLTE Debug Module
[![Analytics](https://ga-beacon.appspot.com/UA-65295275-1/yii2-adminlte-debug)](https://github.com/igrigorik/ga-beacon)

Modified [yiisoft/yii2-debug](https://github.com/yiisoft/yii2-debug)  for [mervick/yii2-adminlte](https://github.com/mervick/yii2-adminlte).

## Installation

This package required by [mervick/yii2-adminlte](https://github.com/mervick/yii2-adminlte).   
If you want install this manually, open terminal and run:

```bash
php composer.phar require "mervick/yii2-adminlte-debug" "*"
```
or add to composer.json
```json
"require": {
    "mervick/yii2-adminlte-debug": "*"
}
```

## Usage

In your config file (it's maybe `config/main.php` or `config/main-local.php`) add the following lines:

```php

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'mervick\adminlte\debug\Module',
        'controllerNamespace' => 'mervick\adminlte\debug\controllers',
        'allowedIPs' => ['*'],   // for all ips with 'dev' role
        'allowedRoles' => ['dev'],
    ];
}
```
