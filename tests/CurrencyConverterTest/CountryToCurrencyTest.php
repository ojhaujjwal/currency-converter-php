<?php
namespace CurrencyConverterTest;

use CurrencyConverter\CountryToCurrency;

class CountryToCurrencyTest extends \PHPUnit_Framework_TestCase
{
    public function testGetCurrency()
    {
        $this->assertEquals('GBP', (new CountryToCurrency)->getCurrency('GB'));
    }

    public function testGetExceptionWithInvalidCurrency()
    {
        $this->setExpectedException('CurrencyConverter\Exception\InvalidArgumentException');
        (new CountryToCurrency)->getCurrency('AB');
    }
}
