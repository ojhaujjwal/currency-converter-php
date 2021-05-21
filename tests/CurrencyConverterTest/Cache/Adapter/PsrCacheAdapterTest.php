<?php
namespace CurrencyConverterTest\Cache\Adapter;

use CurrencyConverter\Cache\Adapter\PsrCacheAdapter;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

class PsrCacheAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CacheItemPoolInterface
     */
    private $cachePool;

    /**
     * @var PsrCacheAdapter
     */
    private $adapter;

    public function setUp()
    {
        $this->cachePool = $this->getMock(CacheItemPoolInterface::class);
        $this->adapter = new PsrCacheAdapter($this->cachePool);
    }

    public function testCacheExists()
    {
        $this->cachePool->expects($this->atLeast(1))
            ->method('hasItem')
            ->will($this->returnValueMap([
                ['USD-GBP', true],
                ['USD-NPR', false]
            ]));

        $this->assertTrue($this->adapter->cacheExists('USD', 'GBP'));
        $this->assertFalse($this->adapter->cacheExists('USD', 'NPR'));
    }

    public function testGetRate()
    {
        $cachePoolItem = $this->getMock(CacheItemInterface::class);
        $cachePoolItem->expects($this->once())
            ->method('get')
            ->willReturn(0.97);

        $this->cachePool->expects($this->once())
            ->method('getItem')
            ->with('USD-GBP')
            ->willReturn($cachePoolItem);

        $this->assertEquals(0.97, $this->adapter->getRate('USD', 'GBP'));
    }

    public function testCreateCache()
    {
        $cachePoolItem = $this->getMock(CacheItemInterface::class);
        $cachePoolItem->expects($this->once())
            ->method('set')
            ->with(0.95);

        $this->cachePool->expects($this->once())
            ->method('getItem')
            ->with('USD-GBP')
            ->willReturn($cachePoolItem);
        $this->cachePool->expects($this->once())
            ->method('save')
            ->with($cachePoolItem);

        $this->adapter->createCache('USD', 'GBP', 0.95);
    }
}
