currency-converter-php
======================

Currency Converter Library with features of caching and identifying currency from country Code

## Why Use It

* Object Oriented and Easy-to-Use
* Reliable Rate, Uses Yahoo API
* Caching of rate, to avoid connecting to Yahoo again and again
* Conversion without curreny code(from country code)


#### Object Oriented and Easy-to-Use
The source code  are fully object oriented and easy to use! You can easily accomodate the source files to your application! 


#### Reliable Rate, Uses Yahoo API
The library uses Yahoo API, "http://download.finance.yahoo.com/d/quotes.csv?s=USDNPR=X&f=nl1d1t1" for getting rates!


#### Caching of rate, to avoid connecting to Yahoo again and again
It might not be intelligent to connect to Yahoo for the same rate regularly. This is not tolerable in production state.
So, this library uses caching system to improve performance! You can easily set cache directory(directory where cache exists) and cache expiry.


#### Conversion without curreny code(from country code)
It might be quiet weird for user to input their currency code or you may have already created application where user enter his/her country. But, you want to know their curreny code!
With this library, you dont have worry about that, because, you can retrieve rates from country code without currency code!


## Requirements

* PHP version 5.3.3 or later
* Curl Extension

## Installation
This library depends on composer(for installation and autoloading and whatever). For installation of composer please visit composer.org 

1. Add `ujjwal/currency-converter` to your composer.json and run `php composer.phar self-update`

## Usage

#### Initializing Conversion
At first you need to call the Library!
```php
use CurrencyConverter\CurrencyConverter;

require_once("vendor/autoload.php");
$converter = new CurrencyConverter;
```


## Setting Cache

#### Enable Cache
Cache is not enabled by default, so you have to enable it first.
```php
$converter->setCachable(true);
//or disable by $converter->setCachable(false);;
```
#### Set Cache Directory
By default, cache directory is currently working directory. So you probably want to change it!
```php
$converter->setCacheDirectory("path/to/your/cache/directory/");
```
If directory does not exists, an Exception is thrown!

#### Set Cache Timeout
By default, cache timeout is 18000 seconds(5 hours) which means connection to Yahoo is done in every 5 hours and after that a new cache is created!
You can change if you like: 
```php
$converter->setCacheTimeOut("time_in_seconds");
```

### Finally getting conversion rate
```php
$amount =  $converter->convert(array('country' => 'US'), array('country' => 'NP'), $amount);// $amount is optional and defaults to 1
```

