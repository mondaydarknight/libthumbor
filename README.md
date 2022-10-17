# libthumbor
A minimal PHP client for generating Thumbor URLs.

## Installation
Run the following command to install the library via [Composer](https://getcomposer.org/doc/).
```console
composer require mondaydarknight/libthumbor
```

This package will be automatically registered using Laravel auto-discovery mechanism, publish the config file of this package with the following command, it'll generate the config file `config/thumbor.php` with required parameters.
```console
php artisan vendor:publish --provider="Thumbor\ThumborServiceProvider" --tag=config
```

## Usage
```php
use Thumbor\Facades\Thumbor;

$url = (string) Thumbor::url('https://images.mamilove.com.tw/brand/0eb36102e3-1574845513.jpeg')
    ->resize(100, 100);
```
