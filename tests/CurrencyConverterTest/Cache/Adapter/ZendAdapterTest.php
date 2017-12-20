<?php

namespace CurrencyConverterTest\Cache\Adapter;

use CurrencyConverter\Cache\Adapter\ZendAdapter;

class ZendAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testCacheExists()
    {
        $mock = $this->getMock('Zend\Cache\Storage\StorageInterface');
        $adapter = new ZendAdapter($mock);
        $mock->expects($this->any())
            ->method('hasItem')
            ->will($this->returnValueMap([['USD-GBP', true], ['USD-NPR', false]]));
        $this->assertTrue($adapter->cacheExists('USD', 'GBP'));
        $this->assertFalse($adapter->cacheExists('USD', 'NPR'));
    }

    public function testSupportedCurrenciesCacheExists()
    {
        $mock = $this->getMock('Zend\Cache\Storage\StorageInterface');
        $adapter = new ZendAdapter($mock);
        $mock->expects($this->any())
            ->method('hasItem')
            ->will($this->returnValueMap([['supported-currencies', true]]));
        $this->assertTrue($adapter->supportedCurrenciesCacheExists());
    }

    public function testGetRate()
    {
        $mock = $this->getMock('Zend\Cache\Storage\StorageInterface');
        $adapter = new ZendAdapter($mock);
        $mock->expects($this->once())
            ->method('getItem')
            ->will($this->returnValue(0.97));
        $this->assertEquals(0.97, $adapter->getRate('USD', 'GBP'));

        $mock = $this->getMock('Zend\Cache\Storage\StorageInterface');
        $adapter = new ZendAdapter($mock);
        $mock->expects($this->once())
            ->method('getItem')
            ->will($this->returnValue(97));
        $this->assertEquals(97, $adapter->getRate('USD', 'NPR'));
    }

    public function testCreateCache()
    {
        $mock = $this->getMock('Zend\Cache\Storage\StorageInterface');
        $adapter = new ZendAdapter($mock);
        $mock->expects($this->once())
            ->method('setItem')
            ->will($this->returnValue(null));
        $adapter->createCache('USD', 'GBP', 0.95);
    }

}
