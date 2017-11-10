<?php
namespace CurrencyConverter\Provider;

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
     * @param Client $httpClient
     */
    public function __construct(Client $httpClient=null)
    {
        $this->httpClient = ($httpClient) ?: new Client();
    }

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
        $result = json_decode($this->httpClient->get($path)->getBody(), true);
        return $result['rates'][$toCurrency];
    }
}
