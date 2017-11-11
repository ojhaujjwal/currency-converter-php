<?php
require __DIR__ . '/../vendor/autoload.php';

$converter = new CurrencyConverter\CurrencyConverter;
echo  $converter->convert('USD', 'EUR');
// or you can do $amount =  $converter->convert(array('country' => 'US'), array('country' => 'NP'));
