<?php

use CurrencyConverter\CurrencyConverter;
use CurrencyConverter\Cache\Adapter\FileSystem;

require '../vendor/autoload.php';

$converter = new CurrencyConverter;
$cacheAdapter = new FileSystem(__DIR__ . '/cache/');
$cacheAdapter->setCacheTimeout(DateInterval::createFromDateString('10 second'));
$converter->setCacheAdapter($cacheAdapter);
echo $converter->convert('USD', 'NPR');
