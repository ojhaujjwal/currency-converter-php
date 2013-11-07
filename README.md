currency-converter-php
======================

Currency Converter Class with features of caching and identifying currency from country Code

## Why Use It
<ul>
  <li>Object Oriented and Easy-to-Use</li>
  <li>Reliable Rate, Uses Yahoo API</li>  
  <li>Caching of rate, to avoid connecting to Yahoo again and again</li>
  <li>Conversion without curreny code(from country code)</li>
</ul>


#### Object Oriented and Easy-to-Use
The source code  are fully object oriented and easy to use! You can easily accomodate the source files to your application! 


#### Reliable Rate, Uses Yahoo API
The class uses Yahoo API, "http://download.finance.yahoo.com/d/quotes.csv?s=USDNPR=X&f=nl1d1t1" for getting rates!


#### Caching of rate, to avoid connecting to Yahoo again and again
It might not be intelligent to connect to Yahoo for the same rate regularly. This is not tolerable in production state.
So, this class uses caching system to improve performance! You can easily set cache directory(directory where cache exists) and cache expiry.


#### Conversion without curreny code(from country code)
It might be quiet weird for user to input their currency code or you may have already created application where user enter his/her country. But, you want to know their curreny code!
With this class, you dont have worry about that, because, you can retrieve rates from country code without currency code!



## Requirements
<ul>
<li>PHP version 5.3.3 or later</li>
<li>Curl Extension</li>
</ul>



## Usage

#### Initializing Conversion
At first you need to call class!
```php
require_once("path/to/CurrencyConverter.php");
$CurrencyConverter = new \library\CurrencyConverter();
```

#### Setting "From" And "To"
```php
$CurrencyConverter->setFromCurrency("USD");
//Or you can do
$CurrencyConverter->setFromCountry("US");
```
Similiarly,
```php
$CurrencyConverter->setToCurrency("NPR");
//Or you can do
$CurrencyConverter->setToCountry("NP");
```

###### Alternatively, you can set these while calling Class!
```php
$CurrencyConverter=new CurrencyConverter(array("country"=>"US"),array("currency"=>"NPR"));
// or  $CurrencyConverter=new CurrencyConverter(array("currency"=>"USD"),array("country"=>"NP"));
// or  $CurrencyConverter=new CurrencyConverter(array("currency"=>"USD"),array("currency"=>"NPR"));
// or  $CurrencyConverter=new CurrencyConverter(array("country"=>"US"),array("country"=>"NP"));
```
First parameter represents "From" credentials and Second parameter represents "To" credentials.


## Setting Cache

#### Enable Cache
Cache is not enabled by default, so you have to enable it first.
```php
$CurrencyConverter->setCachable(true);
//or disable by $CurrencyConverter->setCachable(false);
```
#### Set Cache Directory
By default, cache directory is currently working directory. So you probably want to change it!
```php
$CurrencyConverter->setCacheDirectory("path/to/your/cache/directory/");
```
If directory does not exists, an Exception is thrown!

#### Set Cache Timeout
By default, cache timeout is 18000 seconds(5 hours) which means connection to Google is done in every 5 hours and after that a new cache is created!
You can change if you like: 
```php
$CurrencyConverter->setCacheTimeOut("time_in_seconds");
```

### Finally getting conversion rate
```php
$rate=$CurrencyConverter->convert($amount);// $amount is optional and defaults to 1
```

### Recommended Way
The above way of converting rates is very long. So, I recommend you to create a function which does all the dirty work of setting cache and all you have to do is call the function.
A simple example is shown below::
```php
function convert_currency(array $from, array $to, $amount=1){
    require_once(__DIR__."/../CurrencyConverter.php");
    $CurrencyConverter = new \library\CurrencyConverter($from, $to);
    $CurrencyConverter->setCachable(TRUE);
    $CurrencyConverter->setCacheDirectory(__DIR__."/cache/");
    $CurrencyConverter->setCacheTimeOut(10000);
    return $CurrencyConverter->convert($amount);
}
$rate = convert_currency(array("currency"=>"USD"), array("country"=>"NP"));
echo $rate;
```
Ofcourse, you can change it(convert_currency) as per your requirement!
