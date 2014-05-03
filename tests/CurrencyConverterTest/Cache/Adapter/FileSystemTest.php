<?php
namespace CurrencyConverterTest\Cache\Adapter;

use CurrencyConverter\Cache\Adapter\FileSystem;
use CurrencyConverter\Cache\Adapter\CacheAdapterInterface;
use DateInterval;

class FileSystemTest extends \PHPUnit_Framework_TestCase
{
    public function testSetCachePath()
    {
        $adapter = new FileSystem(__DIR__);
        $this->assertEquals(__DIR__, $adapter->getCachePath());
    }

    public function testGetExceptionWithInvalidCachePath()
    {
        $this->setExpectedException('CurrencyConverter\Cache\Adapter\Exception\CachePathNotFoundException');
        $adapter = new FileSystem(__DIR__ . '/asf/');
    }

    public function testCreateCache()
    {
        $cachePath = __DIR__ . '/../../../resources/cache';
        $adapter = new FileSystem($cachePath);
        $adapter->createCache('ABC', 'DEF', 10);
        $this->assertEquals(10, file_get_contents($cachePath . '/ABC-DEF.cache'));
        $this->assertEquals(10, $adapter->getRate('ABC', 'DEF'));

        return $adapter;
    }

    /**
     * @depends testCreateCache
     */
    public function testCacheExists(CacheAdapterInterface $adapter)
    {
        $adapter->setCacheTimeOut(DateInterval::createFromDateString('123123123 seconds'));
        $this->assertTrue($adapter->cacheExists('ABC', 'DEF'));
    }

    /**
     * @depends testCreateCache
     */
    public function testSetCacheTimeOut(CacheAdapterInterface $adapter)
    {
        $dateInterval = DateInterval::createFromDateString('123123123 seconds');
        $adapter->setCacheTimeOut($dateInterval);
        $this->assertEquals($dateInterval, $adapter->getCacheTimeOut());
    }
}
