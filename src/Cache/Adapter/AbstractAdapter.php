<?php

namespace CurrencyConverter\Cache\Adapter;

use DateInterval;

abstract class AbstractAdapter implements CacheAdapterInterface, ProvidesSupportedCurrenciesCacheInterface
{
    /**
     * Interval of cache life
     *
     * @var DateInterval
     */
    protected $cacheTimeout;

    /**
     * Sets cache timeout
     *
     * @param DateInterval $cacheTimeout
     * @return self
     */
    public function setCacheTimeOut(DateInterval $cacheTimeout)
    {
        $this->cacheTimeout = $cacheTimeout;

        return $this;
    }

    /**
     * Gets cache timeout
     *
     * @return DateInterval
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
        $cacheCreationTime = $this->getCacheCreationTime($fromCurrency, $toCurrency);
        return (time() - $cacheCreationTime) > $this->getCacheTimeOut()->format('%s');
    }

    /**
     * Checks if supported currencies cache is expired
     *
     * @return bool
     */
    protected function isSupportedCurrenciesCacheExpired()
    {
        $cacheCreationTime = $this->getSupportedCurrenciesCacheCreationTime();
        return (time() - $cacheCreationTime) > $this->getCacheTimeOut()->format('%s');
    }

    /**
     * {@inheritDoc}
     */
    public function cacheExists($fromCurrency, $toCurrency)
    {
        return !$this->isCacheExpired($fromCurrency, $toCurrency);
    }

    /**
     * {@inheritDoc}
     */
    public function supportedCurrenciesCacheExists()
    {
        return !$this->isSupportedCurrenciesCacheExpired();
    }

    /**
     * Returns timestamp in which cache was created
     *
     * @param  string $fromCurrency
     * @param  string $toCurrency
     * @return int
     */
    abstract protected function getCacheCreationTime($fromCurrency, $toCurrency);

    /**
     * Returns timestamp in which supported currencies cache was created
     *
     * @return int
     */
    abstract protected function getSupportedCurrenciesCacheCreationTime();

}
