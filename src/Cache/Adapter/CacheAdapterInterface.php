<?php
namespace CurrencyConverter\Cache\Adapter;

interface CacheAdapterInterface
{
    /*
     *  Sets cache timeout
     *
     *  @param float $cacheTimeout Timeout after which cache expires
     *  @return self
     */
    public function setCacheTimeOut($cacheTimeout);

    /**
     *  Gets currently enabled cache timeout
     *  @returns float
     */
    public function getCacheTimeOut();

    /**
     * Checks if cache exists
     *
     * @param  string $fromCurrency
     * @param  string $toCurrency
     * @return bool
     */
    public function cacheExists($fromCurrency, $toCurrency);

    /**
     * Gets exchange rate from cache
     *
     * @param  string $fromCurrency
     * @param  string $toCurrency
     * @return float
     */
    public function getRate($fromCurrency, $toCurrency);

    /**
     * Creates new cache
     *
     * @param  string $fromCurrency
     * @param  string $toCurrency
     *                              @param float rate
     * @return void
     */
    public function createCache($fromCurrency, $toCurrency, $rate);
}
