<?php
namespace CurrencyConverter\Cache\Adapter;

abstract class AbstractAdapter implements CacheAdapterInterface
{
    /**
     * Length of cache time validity in seconds
     *
     * @var float
     */
    protected $cacheTimeout = 18000; // 5 hours

    /**
     * {@inheritDoc)
     */
    public function setCacheTimeOut($cacheTimeout)
    {
        $this->cacheTimeout = $cacheTimeout;

        return $this;
    }

    /**
     * {@inheritDoc)
     */
    public function getCacheTimeOut()
    {
        return $this->cacheTimeout;
    }

    /**
     * Checks if cache is expired
     *
     * @param  string $fromCurrency
     * @param  string $toCurrency
     * @return bool
     */
    protected function isCacheExpired($fromCurrency, $toCurrency)
    {
        return (time() - $this->getCacheCreationTime($fromCurrency, $toCurrency)) > $this->getCacheTimeOut();
    }

    /**
     * Returns timestamp in which cache was created
     *
     * @param  string $fromCurrency
     * @param  string $toCurrency
     * @return int
     */
    abstract protected function getCacheCreationTime($fromCurrency, $toCurrency);
}
