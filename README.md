currency-converter-php
======================

[![Master Branch Build Status](https://api.travis-ci.org/ojhaujjwal/currency-converter-php.png?branch=master)](http://travis-ci.org/ojhaujjwal/currency-converter-php)
[![Latest Stable Version](https://poser.pugx.org/ujjwal/currency-converter/v/stable.png)](https://packagist.org/packages/ujjwal/currency-converter)
[![Latest Unstable Version](https://poser.pugx.org/ujjwal/currency-converter/v/unstable.png)](https://packagist.org/packages/ujjwal/currency-converter)
[![Total Downloads](https://poser.pugx.org/ujjwal/currency-converter/downloads.png)](https://packagist.org/packages/ujjwal/currency-converter)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ojhaujjwal/currency-converter-php/badges/quality-score.png?s=c4d93ce5c60894c09d2b4f7b1ec97d6956c9b23f)](https://scrutinizer-ci.com/g/ojhaujjwal/currency-converter-php/)

Currency Converter Library with features of caching and identifying currency from country Code

## Getting started
```php
<?php
require 'vendor/autoload.php';

$converter = new CurrencyConverter\CurrencyConverter;
echo $converter->convert('USD', 'NPR'); // will print something like 97.44

// caching currency

$cacheAdapter = new CurrencyConverter\Cache\Adapter\FileSystem(__DIR__ . '/cache/');
$cacheAdapter->setCacheTimeout(DateInterval::createFromDateString('10 second'));
$converter->setCacheAdapter($cacheAdapter);
echo $converter->convert('USD', 'NPR');
```

## Why Use It

* Reliable Rate, Uses Yahoo API
* Caching of rate, to avoid connecting to Yahoo again and again
* Conversion without curreny code(from country code)


#### Reliable Rate, Uses Yahoo API
By default, the library uses Yahoo API for getting rates!

#### Caching of rate, to avoid connecting to Yahoo again and again
It might not be intelligent to connect to Yahoo for the same rate regularly. This is not tolerable in production state.
So, this library uses caching system to improve performance! You can easily set cache path and cache expiry.


#### Conversion without curreny code(from country code)
It might be quiet weird for user to input their currency code or you may have already created application where user enter his/her country. But, you want to know their curreny code!
With this library, you dont have worry about that, because, you can retrieve rates from country code without currency code!


## Requirements

* PHP version 5.4 or later
* Curl Extension (Optional)

## Installation
This library depends on composer for installation . For installation of composer, please visit [getcomposer.org](//getcomposer.org). 

Add `"ujjwal/currency-converter":"2.1.*"` to your composer.json and run `php composer.phar update`

## Usage
A simple example is given in [examples/simple.php](https://github.com/ojhaujjwal/currency-converter-php/blob/master/examples/simple.php).

For further documentation, please look at the [/docs](https://github.com/ojhaujjwal/currency-converter-php/tree/master/docs).

