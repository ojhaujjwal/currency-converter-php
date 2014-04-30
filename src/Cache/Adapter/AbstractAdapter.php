<?php
namespace CurrencyConverter\Cache\Adapter;

use DateInterval;

abstract class AbstractAdapter implements CacheAdapterInterface
{
    /**
     * Interval of cache life
     *
     * @var DateInterval
     */
    protected $cacheTimeout;

    /**
     * {@inheritDoc)
     */
    public function setCacheTimeOut(DateInterval $cacheTimeout)
    {
        $this->cacheTimeout = $cacheTimeout;

        return $this;
    }

    /**
     * {@inheritDoc)
     */
    public function getCacheTimeOut()
    {
        if (!$this->cacheTimeout) {
            $this->setCacheTimeOut(DateInterval::createFromDateString('5 hours'));
        }

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
        return (time() - $this->getCacheCreationTime($fromCurrency, $toCurrency)) > $this->getCacheTimeOut()->format('%s');
    }

    /**
     * {@inheritDoc}
     */
    public function cacheExists($fromCurrency, $toCurrency)
    {
        return !$this->isCacheExpired($fromCurrency, $toCurrency);
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
