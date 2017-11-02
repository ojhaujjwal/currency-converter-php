<?php
namespace CurrencyConverter\Provider;


use CurrencyConverter\Provider\Adapter\CurlAdapter;

class YahooApiTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultAdapter()
    {
        $yahoo = new YahooApi();
        $this->assertInstanceOf(CurlAdapter::class, $yahoo->getAdapter());
    }
}
