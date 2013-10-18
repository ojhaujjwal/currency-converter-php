<?php
require(__DIR__."/CurrencyConverter.php");
use library\CurrencyConverter;
$CurrencyConverter=new CurrencyConverter(array("country"=>"US"),array("country"=>"NP"));
$CurrencyConverter->setCachable(TRUE);
$CurrencyConverter->setCacheDirectory(__DIR__."/cache/");
$CurrencyConverter->setCacheTimeOut(100);
echo $CurrencyConverter->convert();
