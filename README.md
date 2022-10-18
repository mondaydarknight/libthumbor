# phumbor
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

$url = (string) Thumbor::url('https://dummyimage.com/600x400.jpg')
    ->resize(100, 100);
```
