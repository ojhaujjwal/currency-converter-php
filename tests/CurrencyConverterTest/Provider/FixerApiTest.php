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
                [FixerApi::FIXER_API_BASEPATH . '?symbols=USD&base=EUR']
            )
            ->will($this->returnValue($response));
        $this->assertEquals(
            1.1645,
            (new FixerApi($httpClient))->getRate('EUR', 'USD')
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
                [FixerApi::FIXER_API_BASEPATH . '?symbols=XXX&base=EUR']
            )
            ->will($this->returnValue($response));

        $this->setExpectedException(UnsupportedCurrencyException::class);
        (new FixerApi($httpClient))->getRate('EUR', 'XXX');
    }

    public function testGetSupportedCurrencies()
    {
        $response = $this->getMock(ResponseInterface::class);
        $response
            ->expects($this->any())
            ->method('getBody')
            ->will($this->returnValue(
                new Stream(fopen('data://text/plain,{"base":"EUR","date":"2017-12-08","rates":{"AUD":1.562,"BGN":1.9558,"BRL":3.8435,"CAD":1.5072,"CHF":1.1704,"CNY":7.7729,"CZK":25.555,"DKK":7.4417,"GBP":0.87525,"HKD":9.1661,"HRK":7.5493,"HUF":314.5,"IDR":15910.0,"ILS":4.1343,"INR":75.678,"JPY":133.26,"KRW":1285.3,"MXN":22.22,"MYR":4.8001,"NOK":9.7665,"NZD":1.7157,"PHP":59.336,"PLN":4.202,"RON":4.6336,"RUB":69.651,"SEK":9.977,"SGD":1.5889,"THB":38.361,"TRY":4.5165,"USD":1.1742,"ZAR":16.039}}', 'r'))
            ));
        $httpClient = $this->getMock(Client::class);
        $httpClient
            ->expects($this->once())
            ->method('__call')
            ->with(
                'get',
                [FixerApi::FIXER_API_BASEPATH]
            )
            ->will($this->returnValue($response));
        $this->assertEquals(
            ['EUR', 'AUD', 'BGN', 'BRL', 'CAD', 'CHF', 'CNY', 'CZK', 'DKK', 'GBP', 'HKD', 'HRK', 'HUF', 'IDR', 'ILS', 'INR', 'JPY', 'KRW', 'MXN', 'MYR', 'NOK', 'NZD', 'PHP', 'PLN', 'RON', 'RUB', 'SEK', 'SGD', 'THB', 'TRY', 'USD', 'ZAR'],
            (new FixerApi($httpClient))->getSupportedCurrencies('EUR', 'USD')
        );
    }
}
