<?php

use CurrencyConverter\CurrencyConverter;
use CurrencyConverter\Cache\Adapter\FileSystem;
    
chdir(dirname(__DIR__));

require 'vendor/autoload.php';

$converter = new CurrencyConverter;
$converter->setCachable();
$cacheAdapter = new FileSystem(__DIR__ . '/cache/');
$cacheAdapter->setCacheTimeout(10);
$converter->setCacheAdapter($cacheAdapter);
$amount =  $converter->convert(array('country' => 'US'), array('country' => 'NP'));

echo $amount;
