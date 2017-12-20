<?php

namespace CurrencyConverter\Cache\Adapter;

interface ProvidesSupportedCurrenciesCacheInterface
{
    const   SUPPORTED_CURRENCIES_CACHE_KEY_NAME = 'supported-currencies';

    /**
     * Checks if supported currencies cache exists
     *
     * @return bool
     */
    public function supportedCurrenciesCacheExists();

    /**
     * Gets supported currencies from cache
     *
     * @return float
     */
    public function getSupportedCurrencies();

    /**
     * Creates new supported currencies cache
     *
     * @param array $currencyList
     */
    public function createSupportedCurrenciesCache($currencyList);
}
