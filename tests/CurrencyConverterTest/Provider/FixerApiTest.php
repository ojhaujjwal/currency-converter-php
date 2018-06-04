<?php

namespace CurrencyConverter\Provider;

use CurrencyConverter\Exception\UnsupportedCurrencyException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\ResponseInterface;

class FixerApiTest extends \PHPUnit_Framework_TestCase
{
    public function testGetRate()
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
            ->method('__call')
            ->with(
                'get',
                ['http://' . FixerApi::FIXER_API_BASEPATH . '?access_key=SOME-ACCESS-KEY&symbols=USD&base=EUR']
            )
            ->will($this->returnValue($response));
        $this->assertEquals(
            1.1645,
            (new FixerApi('SOME-ACCESS-KEY', $httpClient))->getRate('EUR', 'USD')
        );
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
            ->method('__call')
            ->with(
                'get',
                ['http://' . FixerApi::FIXER_API_BASEPATH . '?access_key=SOME-ACCESS-KEY&symbols=XXX&base=EUR']
            )
            ->will($this->returnValue($response));

        $this->setExpectedException(UnsupportedCurrencyException::class);
        (new FixerApi('SOME-ACCESS-KEY', $httpClient))->getRate('EUR', 'XXX');
    }

    public function testHttpsUsage()
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
            ->method('__call')
            ->with(
                'get',
                ['https://' . FixerApi::FIXER_API_BASEPATH . '?access_key=SOME-ACCESS-KEY&symbols=USD&base=EUR']
            )
            ->will($this->returnValue($response));
        $this->assertEquals(
            1.1645,
            (new FixerApi('SOME-ACCESS-KEY', $httpClient, true))->getRate('EUR', 'USD')
        );
    }
    
}
