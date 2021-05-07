<?php
namespace CurrencyConverter\Provider;

use GuzzleHttp\Client;

/**
 * Fetch rates from https://fixer.io
 */
class FixerApi extends AbstractFixerApi
{
    /**
     * Base url of fixer api
     *
     * @var string
     */
    const FIXER_API_BASEPATH = 'data.fixer.io/api/latest';

    /**
     * FixerApi constructor.
     *
     * @param string $accessKey
     * @param Client|null $httpClient
     * @param bool $useHttps defaults to false
     */
    public function __construct($accessKey, Client $httpClient = null, $useHttps = false)
    {
        $this->setAccessKey($accessKey);
        $this->setUseHttps($useHttps);
        $this->setBasePath(self::FIXER_API_BASEPATH);

        if ($httpClient !== null) {
            $this->setHttpClient($httpClient);
        }
    }
}
