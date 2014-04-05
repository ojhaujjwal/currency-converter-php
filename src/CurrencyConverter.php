<?php

namespace CurrencyConverter;

class CurrencyConverter
{
	/**
	 * store cache or not
     *
     * @var bool
	 */
	protected $cachable = false;

    /**
     * @var Provider\ProviderInterface
     */
    protected $rateProvider;

    /**
     * @var Cache\Adapter\CacheAdapterInterface
     */
    protected $cacheAdapter;

    /**
     * Converts currency from one to another
     *
     * @param array|string $from
     * @param array|string $to
     * @param float optional $amount
     *
     * @return float
     */
    public function convert($from, $to, $amount = 1)
    {
        if (is_string($from)) {
            $fromCurrency = $from;
        } elseif (is_array($from)) {
            if (isset($from['country'])) {
                $fromCurrency = CountryToCurrency::getCurrency($from['country']);
            } elseif (isset($from['currency'])) {
                $fromCurrency = $from['currency'];
            } else {
                throw new Exception\InvalidArgumentException('Please provide country or currency under from');
            }            
        } else {
            
        }
        if (is_string($to)) {
            $toCurrency = $to;
        } elseif (is_array($to)) {
            if (isset($to['country'])) {
                $toCurrency = CountryToCurrency::getCurrency($to['country']);
            } elseif (isset($to['currency'])) {
                $toCurrency = $to['currency'];
            } else {
                throw new Exception\InvalidArgumentException('Please provide country or currency under from');
            }            
        } else {
            
        }

        if ($this->isCacheable()) {
            if ($this->getCacheAdapter()->cacheExists($fromCurrency, $toCurrency)) {
                return $this->getCacheAdapter()->getRate($fromCurrency, $toCurrency) * $amount;
            } elseif ($this->getCacheAdapter()->cacheExists($toCurrency, $fromCurrency)) {
                return (1 / $this->getCacheAdapter()->getRate($toCurrency, $fromCurrency)) * $amount;
            }            
        }

        $rate = $this->getRateProvider()->getRate($fromCurrency, $toCurrency);

        if ($this->isCacheable()) {
            $this->getCacheAdapter()->createCache($fromCurrency, $toCurrency, $rate);
        }

        return $rate * $amount;
    }

    /**
     * Sets if caching is to be enables
     *
     * @param boolean $cachable
     * @return self
     */
    public function setCachable($cachable = true)
    {
        $this->cachable = (bool) $cachable;

        return $this;
    }

    /**
     * Checks if caching is enabled
     *
     * @return bool
     */
    public function isCacheable()
    {
        return $this->cachable;
    }

    /**
     * Gets Rate Provider
     *
     * @return Provider\ProviderInterface
     */
    public function getRateProvider()
    {
        if (!$this->rateProvider) {
            $this->setRateProvider(new Provider\YahooApi());
        }

        return $this->rateProvider;
    }

    /**
     * Sets rate provider
     *
     * @param Provider\ProviderInterface $rateProvider
     * @return self
     *
     */
    public function setRateProvider(Provider\ProviderInterface $rateProvider)
    {
        $this->rateProvider = $rateProvider;

        return $this;
    }

    /**
     * Sets cache adapter
     *
     * @param Cache\Adapter\CacheAdapterInterface $cacheAdapter
     * @return self
     */
    public function setCacheAdapter(Cache\Adapter\CacheAdapterInterface $cacheAdapter)
    {
        $this->setCachable(true);
        $this->cacheAdapter = $cacheAdapter;

        return $this;
    }

    /**
     * Gets cache adapter
     *
     * @return Cache\Adapter\CacheAdapterInterface
     */
    public function getCacheAdapter()
    {
        if (!$this->cacheAdapter) {
            $this->setCacheAdapter(new Cache\Adapter\FileSystem());
        }

        return $this->cacheAdapter;
    }
}
