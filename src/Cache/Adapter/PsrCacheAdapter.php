<?php
namespace CurrencyConverter\Cache\Adapter;

use Psr\Cache\CacheItemPoolInterface;

class PsrCacheAdapter implements CacheAdapterInterface
{
    /**
     * @var CacheItemPoolInterface
     */
    private $cachePool;

    /**
     * Constructor
     *
     * @param CacheItemPoolInterface $cachePool
     */
    public function __construct(CacheItemPoolInterface $cachePool)
    {
        $this->cachePool = $cachePool;
    }

    /**
     * {@inheritDoc}
     */
    public function cacheExists($fromCurrency, $toCurrency)
    {
        return $this->cachePool->hasItem($this->getCacheItemName($fromCurrency, $toCurrency));
    }

    /**
     * {@inheritDoc}
     */
    public function getRate($fromCurrency, $toCurrency)
    {
        return $this->cachePool->getItem($this->getCacheItemName($fromCurrency, $toCurrency))->get();
    }

    /**
     * {@inheritDoc}
     */
    public function createCache($fromCurrency, $toCurrency, $rate)
    {
        $cacheItem = $this->cachePool->getItem($this->getCacheItemName($fromCurrency, $toCurrency));
        $cacheItem->set($rate);

        return $this->cachePool->save($cacheItem);
    }

    protected function getCacheItemName($fromCurrency, $toCurrency)
    {
        return $fromCurrency . '-' . $toCurrency;
    }
}
