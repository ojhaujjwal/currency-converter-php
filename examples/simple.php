<?php

use CurrencyConverter\CurrencyConverter;
use CurrencyConverter\Cache\Adapter\FileSystem;

require '../vendor/autoload.php';

$converter = new CurrencyConverter;
echo  $converter->convert('USD', 'NPR');
// or you can do $amount =  $converter->convert(array('country' => 'US'), array('country' => 'NP'));
