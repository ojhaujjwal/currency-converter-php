currency-converter-php
======================

[![Master Branch Build Status](https://api.travis-ci.org/ojhaujjwal/currency-converter-php.png?branch=master)](http://travis-ci.org/ojhaujjwal/currency-converter-php)
[![Latest Stable Version](https://poser.pugx.org/ujjwal/currency-converter/v/stable.png)](https://packagist.org/packages/ujjwal/currency-converter)
[![Latest Unstable Version](https://poser.pugx.org/ujjwal/currency-converter/v/unstable.png)](https://packagist.org/packages/ujjwal/currency-converter)
[![Total Downloads](https://poser.pugx.org/ujjwal/currency-converter/downloads.png)](https://packagist.org/packages/ujjwal/currency-converter)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ojhaujjwal/currency-converter-php/badges/quality-score.png?s=c4d93ce5c60894c09d2b4f7b1ec97d6956c9b23f)](https://scrutinizer-ci.com/g/ojhaujjwal/currency-converter-php/)
[![Code Coverage](https://scrutinizer-ci.com/g/ojhaujjwal/currency-converter-php/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/ojhaujjwal/currency-converter-php/?branch=master)

Exchange rates/Currency Converter Library with features of caching and identifying currency from country code.

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


## Requirements

* PHP version 5.5 or later
* Curl Extension (Optional)

## Installation
This library depends on composer for installation . For installation of composer, please visit [getcomposer.org](//getcomposer.org). 

Add `"ujjwal/currency-converter":"2.2.*"` to your composer.json and run `php composer.phar update`

## Usage
Please head on to [/examples](/examples) folder.

For further documentation, please look at the [/docs](https://github.com/ojhaujjwal/currency-converter-php/tree/master/docs).

