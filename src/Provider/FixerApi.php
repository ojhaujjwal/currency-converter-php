<?php
namespace CurrencyConverter\Provider;

use CurrencyConverter\Provider\Adapter\AdapterInterface;
use CurrencyConverter\Provider\Adapter\CurlAdapter;

/**
 * Fetch rates from https://fixer.io
 *
 * @package CurrencyConverter\Provider
 */
class FixerApi implements ProviderInterface
{
    /**
     * Base url of fixer api
     *
     * @var string
     */
    const FIXER_API_BASEPATH = 'https://api.fixer.io/latest';

    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @inheritdoc
     */
    public function getRate($fromCurrency, $toCurrency)
    {
        $path = sprintf(
            self::FIXER_API_BASEPATH . '?symbols=%s&base=%s',
            $toCurrency,
            $fromCurrency
        );
        $result = json_decode($this->getAdapter()->getFile($path));

        return $result->rates->$toCurrency;
    }

    /**
     * @return AdapterInterface
     */
    public function getAdapter(): AdapterInterface
    {
        if (null === $this->adapter) {
            $this->setAdapter(new CurlAdapter());
        }
        return $this->adapter;
    }

    /**
     * @param AdapterInterface $adapter
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }
}