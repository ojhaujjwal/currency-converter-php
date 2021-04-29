<?php
require __DIR__ . '/../vendor/autoload.php';

$converter = new CurrencyConverter\CurrencyConverter;
$converter->setRateProvider(new \CurrencyConverter\Provider\FrankfurterApi());

echo $converter->convert('USD', 'EUR');
