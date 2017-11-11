<?php
require __DIR__ . '/../vendor/autoload.php';

$converter = new CurrencyConverter\CurrencyConverter;
$cacheAdapter = new CurrencyConverter\Cache\Adapter\FileSystem(__DIR__ . '/cache/');
$cacheAdapter->setCacheTimeout(DateInterval::createFromDateString('10 second'));
$converter->setCacheAdapter($cacheAdapter);
echo $converter->convert('USD', 'EUR');
