Cache Adapters
===============

Cache adapters are classes that store and cache currency exchange rates. A cache adapter must implement `CurrencyConverter\Cache\Adapter\CacheAdapterInterface`.

This library comes with just a cache adapter, `CurrencyConverter\Cache\Adapter\FileSystem` which stores cache in file system.

## Creating custom adapters

Lets create a adapter which uses session to store cache and retrive cache.

```php
namespace Application\CurrencyConverter\Cache\Adapter;

use CurrencyConverter\Cache\Adapter\CacheAdapterInterface;

class Session implements CacheAdapterInterface
{
	protected $cacheTimeout = 18000; // 5 hours    

    public function setCacheTimeOut($cacheTimeout)
    {
        $this->cacheTimeout = $cacheTimeout;
    }

    public function getCacheTimeOut()
    {
        return $this->cacheTimeout;
    }

    public function cacheExists($fromCurrency, $toCurrency)
    {
        if (isset($_SESSION['exchange_rates'][$fromCurrency][$toCurrency])) {
            $storage = $_SESSION['exchange_rates'][$fromCurrency][$toCurrency];
            return $storage['created_time'] < (time() - $this->getCacheTimeOut());
        }
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

## A better way
You can also create a cache adapter by extending `CurrencyConverter\Cache\Adapter\AbstractAdapter`:
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