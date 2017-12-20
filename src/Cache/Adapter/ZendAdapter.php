<?php

namespace CurrencyConverter\Cache\Adapter;

use Zend\Cache\Storage\StorageInterface;

/**
 * An abstraction layer between this library and Zend Cache Component
 */
class ZendAdapter implements CacheAdapterInterface, ProvidesSupportedCurrenciesCacheInterface
{
    /**
     * @var StorageInterface
     */
    protected $storage;

    /**
     * Constructor
     *
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * {@inheritDoc}
     */
    public function cacheExists($fromCurrency, $toCurrency)
    {
        return $this->storage->hasItem($this->getCacheItemName($fromCurrency, $toCurrency));
    }

    /**
     * {@inheritDoc}
     */
    public function getRate($fromCurrency, $toCurrency)
    {
        return $this->storage->getItem($this->getCacheItemName($fromCurrency, $toCurrency));
    }

    /**
     * {@inheritDoc}
     */
    public function createCache($fromCurrency, $toCurrency, $rate)
    {
        return $this->storage->setItem($this->getCacheItemName($fromCurrency, $toCurrency), $rate);
    }

    /**
     * {@inheritDoc}
     */
    protected function getCacheItemName($fromCurrency, $toCurrency)
    {
        return $fromCurrency . '-' . $toCurrency;
    }

    /**
     * {@inheritDoc}
     */
    public function supportedCurrenciesCacheExists()
    {
        return $this->storage->hasItem(ProvidesSupportedCurrenciesCacheInterface::SUPPORTED_CURRENCIES_CACHE_KEY_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getSupportedCurrencies()
    {
        return $this->storage->getItem(ProvidesSupportedCurrenciesCacheInterface::SUPPORTED_CURRENCIES_CACHE_KEY_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function createSupportedCurrenciesCache($currencyList)
    {
        return $this->storage->setItem(ProvidesSupportedCurrenciesCacheInterface::SUPPORTED_CURRENCIES_CACHE_KEY_NAME, $currencyList);
    }
}
