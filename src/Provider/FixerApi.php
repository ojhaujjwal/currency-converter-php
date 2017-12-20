<?php

namespace CurrencyConverter\Provider;

use CurrencyConverter\Exception\UnsupportedCurrencyException;
use GuzzleHttp\Client;

/**
 * Fetch rates from https://fixer.io
 *
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
     * @var Client
     */
    protected $httpClient;

    /**
     * FixerApi constructor.
     * @param Client|null $httpClient
     */
    public function __construct(Client $httpClient = null)
    {
        $this->httpClient = $httpClient ?: new Client();
    }

    /**
     * {@inheritdoc}
     */
    public function getRate($fromCurrency, $toCurrency)
    {
        $path = sprintf(
            self::FIXER_API_BASEPATH . '?symbols=%s&base=%s',
            $toCurrency,
            $fromCurrency
        );
        $result = json_decode($this->httpClient->get($path)->getBody(), true);

        if (!isset($result['rates'][$toCurrency])) {
            throw new UnsupportedCurrencyException(sprintf(UnsupportedCurrencyException::UNSUPPORTED_CURRENCY_MSG, $toCurrency));
        }

        return $result['rates'][$toCurrency];
    }

    /**
     * {@inheritdoc}
     */
    public function getSupportedCurrencies()
    {
        $result = json_decode($this->httpClient->get(self::FIXER_API_BASEPATH)->getBody(), true);
        return array_merge([$result['base']], array_keys($result['rates']));
    }
}
