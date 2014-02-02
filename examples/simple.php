<?php

use CurrencyConverter\CurrencyConverter;
    
chdir(dirname(__DIR__));

require 'vendor/autoload.php';

$converter = new CurrencyConverter;
$converter->setCachable(true);
$converter->setCacheDirectory(__DIR__ . '/cache/');
$converter->setCacheTimeOut(100);
$amount =  $converter->convert(array('country' => 'US'), array('country' => 'NP'));

echo $amount;
