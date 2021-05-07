<?php
namespace CurrencyConverter\Provider;

use CurrencyConverter\Exception\UnsupportedCurrencyException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\ResponseInterface;

class FranfurterApiTest extends \PHPUnit_Framework_TestCase
{
    public function testGetRateWithDefaults()
    {
        $response = $this->getMock(ResponseInterface::class);
        $response
            ->expects($this->any())
            ->method('getBody')
            ->will($this->returnValue(
                new Stream(fopen('data://text/plain,{"base":"EUR","date":"2017-11-02","rates":{"USD":1.1645}}', 'r'))
            ));
        $httpClient = $this->getMock(Client::class);
        $httpClient
            ->expects($this->once())
            ->method('get')
            ->with('https://' . FrankfurterApi::DEFAULT_BASEPATH . '?symbols=USD&base=EUR')
            ->will($this->returnValue($response));

        $provider = new FrankfurterApi();
        $provider->setHttpClient($httpClient);

        $this->assertEquals(1.1645, $provider->getRate('EUR', 'USD'));
    }

    public function testGetUnavailableRate()
    {
        $response = $this->getMock(ResponseInterface::class);
        $response
            ->expects($this->any())
            ->method('getBody')
            ->will($this->returnValue(
                new Stream(fopen('data://text/plain,{"base":"EUR","date":"2017-11-28","rates":{}}', 'r'))
            ));
        $httpClient = $this->getMock(Client::class);
        $httpClient
            ->expects($this->once())
            ->method('get')
            ->with('https://' . FrankfurterApi::DEFAULT_BASEPATH . '?symbols=XXX&base=EUR')
            ->will($this->returnValue($response));

        $this->setExpectedException(UnsupportedCurrencyException::class);

        $provider = new FrankfurterApi();
        $provider->setHttpClient($httpClient);
        $provider->getRate('EUR', 'XXX');
    }

    public function dataUrlOptions()
    {
        return [
            [
                'http://' . FrankfurterApi::DEFAULT_BASEPATH . '?symbols=USD&base=EUR',
                FrankfurterApi::DEFAULT_BASEPATH,
                false
            ],
            [
                'https://' . FrankfurterApi::DEFAULT_BASEPATH . '?symbols=USD&base=EUR',
                FrankfurterApi::DEFAULT_BASEPATH,
                true
            ],
            [
                'http://my-instance.dev/latest?symbols=USD&base=EUR',
                'my-instance.dev/latest',
                false
            ],
        ];
    }

    /**
     * @dataProvider dataUrlOptions
     *
     * @param string $expectedRequestUrl
     * @param string $basePath
     * @param boolean $useHttps
     */
    public function testGetRate($expectedRequestUrl, $basePath, $useHttps)
    {
        $response = $this->getMock(ResponseInterface::class);
        $response
            ->expects($this->any())
            ->method('getBody')
            ->will($this->returnValue(
                new Stream(fopen('data://text/plain,{"base":"EUR","date":"2017-11-02","rates":{"USD":1.1645}}', 'r'))
            ));
        $httpClient = $this->getMock(Client::class);
        $httpClient
            ->expects($this->once())
            ->method('get')
            ->with($expectedRequestUrl)
            ->will($this->returnValue($response));

        $provider = new FrankfurterApi($basePath, $useHttps);
        $provider->setHttpClient($httpClient);

        $this->assertEquals(1.1645, $provider->getRate('EUR', 'USD'));
    }
}
