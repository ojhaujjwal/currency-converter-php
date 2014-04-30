<?php
require '../vendor/autoload.php';

$converter = new CurrencyConverter\CurrencyConverter;
echo  $converter->convert('USD', 'NPR');
// or you can do $amount =  $converter->convert(array('country' => 'US'), array('country' => 'NP'));
