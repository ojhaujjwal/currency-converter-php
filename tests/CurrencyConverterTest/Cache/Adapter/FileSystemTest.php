<?php
namespace CurrencyConverterTest\Cache\Adapter;

use CurrencyConverter\Cache\Adapter\FileSystem;
use DateInterval;

class FileSystemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FileSystem
     */
    private $adapter;

    /**
     * @var string
     */
    private $cachePath;

    public function setUp()
    {
        $this->cachePath = __DIR__ . '/../../../resources/cache';
        $this->adapter = new FileSystem($this->cachePath);
    }

    public function testSetCachePath()
    {
        $this->assertEquals($this->cachePath, $this->adapter->getCachePath());
    }

    public function testGetExceptionWithInvalidCachePath()
    {
        $this->setExpectedException('CurrencyConverter\Cache\Adapter\Exception\CachePathNotFoundException');
        new FileSystem(__DIR__ . '/asf/');
    }

    public function testCreateCache()
    {
        $this->adapter->createCache('ABC', 'DEF', 10);

        $this->assertEquals(10, file_get_contents($this->cachePath . '/ABC-DEF.cache'));
        $this->assertEquals(10, $this->adapter->getRate('ABC', 'DEF'));
    }

    public function dataCacheExists()
    {
        return [
            [true, '123123123 seconds'],
            [true, '2 day'],
            [true, '5 hours'],
            [true, '1 hour'],
            [false, '60 seconds'],
            [false, '1 minute'],
        ];
    }

    /**
     * @dataProvider dataCacheExists
     * @param bool $expectedResult
     * @param string $dateInterval
     */
    public function testCacheExists($expectedResult, $dateInterval)
    {
        // Simulate 1h old cache item
        touch($this->cachePath . '/ABC-DEF.cache', time() - 3600);

        $this->adapter->setCacheTimeOut(DateInterval::createFromDateString($dateInterval));
        $this->assertSame($expectedResult, $this->adapter->cacheExists('ABC', 'DEF'));
    }

    public function testSetCacheTimeOut()
    {
        $dateInterval = DateInterval::createFromDateString('10 seconds');
        $this->adapter->setCacheTimeOut($dateInterval);

        $this->assertEquals($dateInterval, $this->adapter->getCacheTimeOut());
    }
}
