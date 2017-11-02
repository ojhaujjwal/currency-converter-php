<?php
namespace CurrencyConverter\Provider;

use CurrencyConverter\Provider\Adapter\CurlAdapter;

class YahooApi implements ProviderInterface
{
    /**
     * Url where Curl request is made
     *
     * @var strig
     */
    const API_URL = 'http://download.finance.yahoo.com/d/quotes.csv?s=[fromCurrency][toCurrency]=X&f=nl1d1t1';

    /**
     * @var CurlAdapter
     */
    protected $adapter;

    /**
     * {@inheritDoc}
     */
    public function getRate($fromCurrency, $toCurrency)
    {
        $fromCurrency = urlencode($fromCurrency);
        $toCurrency = urlencode($toCurrency);

        $url = str_replace(
            ['[fromCurrency]', '[toCurrency]'],
            [$fromCurrency, $toCurrency],
            static::API_URL
        );
        $rawdata = $this->getAdapter()->getFile($url);

        return explode(',', $rawdata)[1];
    }

    /**
     * @return CurlAdapter
     */
    public function getAdapter()
    {
        if (null === $this->adapter) {
            $this->setAdapter(new CurlAdapter());
        }
        return $this->adapter;
    }

    /**
     * @param CurlAdapter $adapter
     */
    public function setAdapter(CurlAdapter $adapter)
    {
        $this->adapter = $adapter;
    }


}
