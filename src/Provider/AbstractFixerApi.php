<?php
namespace CurrencyConverter\Provider;

use CurrencyConverter\Exception\UnsupportedCurrencyException;
use GuzzleHttp\Client;

abstract class AbstractFixerApi implements ProviderInterface
{
    /**
     * @var string
     */
    protected $basePath;

    /**
     * @var string|null
     */
    protected $accessKey = null;

    /**
     * @var Client
     */
    protected $httpClient;

    /**
     * @var bool
     */
    protected $useHttps = false;

    /**
     * @param string $basePath
     * @return AbstractFixerApi
     */
    public function setBasePath($basePath)
    {
        $this->basePath = (string) $basePath;
        return $this;
    }

    /**
     * @param string|null $accessKey
     * @return AbstractFixerApi
     */
    public function setAccessKey($accessKey)
    {
        $this->accessKey = $accessKey;
        return $this;
    }

    /**
     * @param Client|null $httpClient
     * @return AbstractFixerApi
     */
    public function setHttpClient(Client $httpClient = null)
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    /**
     * @return Client
     */
    protected function getHttpClient()
    {
        if ($this->httpClient === null) {
            $this->httpClient = new Client();
        }
        return $this->httpClient;
    }

    /**
     * @param bool $useHttps
     * @return AbstractFixerApi
     */
    public function setUseHttps($useHttps)
    {
        $this->useHttps = (boolean) $useHttps;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRate($fromCurrency, $toCurrency)
    {
        $params = [];
        if ($this->accessKey !== null) {
            $params['access_key'] = $this->accessKey;
        }
        $params['symbols'] = $toCurrency;
        $params['base'] = $fromCurrency;

        $path = ($this->useHttps ? 'https://' : 'http://') . $this->basePath . '?' . http_build_query($params);

        $result = json_decode($this->getHttpClient()->get($path)->getBody(), true);

        if (!isset($result['rates'][$toCurrency])) {
            throw new UnsupportedCurrencyException(sprintf('Undefined rate for "%s" currency.', $toCurrency));
        }

        return $result['rates'][$toCurrency];
    }
}
