<?php
namespace CurrencyConverterTest;

use CurrencyConverter\CurrencyConverter;

class CurrencyConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testConversionWithoutCache()
    {
        $converter = new CurrencyConverter;
        $rateProvider = $this->getMock('CurrencyConverter\Provider\ProviderInterface');
        $rateProvider->expects($this->any())
            ->method('getRate')
            ->will($this->returnValue(97));
        $converter->setRateProvider($rateProvider);
        $this->assertEquals(97, $converter->convert('USD', 'EUR'));
    }

    public function testSimpleConversionWithCache()
    {
        $converter = new CurrencyConverter;
        $cacheAdapter = $this->getMock('CurrencyConverter\Cache\Adapter\FileSystem');
        $map = [
            ['USD', 'NPR', 97]        
        ];
        $cacheAdapter->expects($this->any())
            ->method('getRate')
            ->will($this->returnValueMap($map));
        $map = [
            ['USD', 'NPR', true],
            ['NPR', 'USD', false]                
        ];
        $cacheAdapter->expects($this->any())
            ->method('cacheExists')
            ->will($this->returnValueMap($map));
        $converter->setCacheAdapter($cacheAdapter);
        $this->assertEquals(97, $converter->convert('USD', 'NPR'));
        $this->assertEquals(1 / 97, $converter->convert('NPR', 'USD'));
        $this->assertEquals(97, $converter->convert(['country' => 'US'], ['country' => 'NP']));
        $this->assertEquals(1 / 97, $converter->convert(['country' => 'NP'], ['country' => 'US']));

    }

    public function testGetExceptionWithInvalidCurrencyArgument()
    {
        $converter = new CurrencyConverter;
        $this->setExpectedException('CurrencyConverter\Exception\InvalidArgumentException');
        $converter->convert([], []);
        $converter->convert(29, 456);
    }
}
