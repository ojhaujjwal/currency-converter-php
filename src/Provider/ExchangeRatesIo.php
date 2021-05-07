<?php
namespace CurrencyConverter\Provider;

use GuzzleHttp\Client;

/**
 * Get exchange rates from https://exchangeratesapi.io/
 */
class ExchangeRatesIo extends AbstractFixerApi
{
    const EXCHANGERATESIO_API_BASEPATH = 'api.exchangeratesapi.io/latest';

    /**
     * @param Client|null $httpClient
     */
    public function __construct(Client $httpClient = null)
    {
        $this->setBasePath(self::EXCHANGERATESIO_API_BASEPATH);
        $this->setUseHttps(true);

        if ($httpClient !== null) {
            $this->setHttpClient($httpClient);
        }
    }
}
