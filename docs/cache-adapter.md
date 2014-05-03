Cache Adapters
===============

Cache adapters are classes that store and cache currency exchange rates. A cache adapter must implement `CurrencyConverter\Cache\Adapter\CacheAdapterInterface`.

This library comes with just two cache adapters, `CurrencyConverter\Cache\Adapter\FileSystem` which stores cache in file system and `CurrencyConverter\Cache\Adapter\ZendAdapter` which acts as an abstraction layer between this library and [Zend Framework 2](https://github.com/zendframework/zf2) Cache Component.

## Creating custom adapters

Lets create a adapter which uses session to store cache.

```php
namespace Application\CurrencyConverter\Cache\Adapter;

use CurrencyConverter\Cache\Adapter\CacheAdapterInterface;

class Session implements CacheAdapterInterface
{
    public function cacheExists($fromCurrency, $toCurrency)
    {
        return isset($_SESSION['exchange_rates'][$fromCurrency][$toCurrency]);
    }

    public function getRate($fromCurrency, $toCurrency)
    {
        return $_SESSION['exchange_rates'][$fromCurrency][$toCurrency]['rate'];
    }

    public function createCache($fromCurrency, $toCurrency, $rate)
    {
        $_SESSION['exchange_rates'][$fromCurrency][$toCurrency]['rate'] = $rate;
    }
        
}
```

## A better way
But, the above cache adapter does not have expiry of cached rates. So, a better way is to extend `CurrencyConverter\Cache\Adapter\AbstractAdapter`:
```php
namespace Application\CurrencyConverter\Cache\Adapter;

use CurrencyConverter\Cache\Adapter\AbstractAdapter;

class Session extends AbstractAdapter
{
    public function cacheExists($fromCurrency, $toCurrency)
    {
        if (!isset($_SESSION['exchange_rates'][$fromCurrency][$toCurrency])) {
            return false;
        }

        return !$this->isCacheExpired($fromCurrency, $toCurrency);
    }

    protected function getCacheCreationTime($fromCurrency, $toCurrency)
    {
        return $_SESSION['exchange_rates'][$fromCurrency][$toCurrency]['created_time'];
    }

    public function getRate($fromCurrency, $toCurrency)
    {
        $_SESSION['exchange_rates'][$fromCurrency][$toCurrency]['rate'];
    }

    public function createCache($fromCurrency, $toCurrency, $rate)
    {
        $_SESSION['exchange_rates'][$fromCurrency][$toCurrency]['rate'] = $rate;
        $_SESSION['exchange_rates'][$fromCurrency][$toCurrency]['created_time'] = time();
    }
        
}
```

Now let`s use it:

```php
<?php

use CurrencyConverter\CurrencyConverter;
use Application\Cache\Adapter\Session;
require 'vendor/autoload.php';
$converter = new CurrencyConverter;
$cacheAdapter = new Session;
$cacheAdapter->setCacheTimeout(DateInterval::createFromDateString('10 seconds'));
$converter->setCacheAdapter($cacheAdapter);
echo  $converter->convert('USD', 'NPR'); // will print something like 97
```
