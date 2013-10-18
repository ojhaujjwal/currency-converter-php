<?php
function convert_currency(array $from, array $to, $amount){
    require(__DIR__."/../CurrencyConverter.php");
    $CurrencyConverter = new CurrencyConverter($from, $to);
    $CurrencyConverter->setCachable(TRUE);
    $CurrencyConverter->setCacheDirectory(__DIR__."/cache/");
    $CurrencyConverter->setCacheTimeOut(10000);
    return $CurrencyConverter->convert($amount);
}
