# CHANGELOG

### 2.0
* Caching has been removed from the main `CurrencyConverter` and moved to a separate namespace
* Connection to Yahoo has been removed from the main `CurrencyConverter` class and moved to a separate namespace
* Addition of cache adapters
* Additions of rate providers

### 2.1.0
* Used DateInterval class instead of seconds for cache time out

### 2.1.1
* Added Zend Cache Adapter
* Removed `setCacheTimeOut` and `getCacheTimeOut` from `CurrencyConverter\Cache\Adapter\CacheAdapterInterface`.