<?php
namespace CurrencyConverter\Provider;

use CurrencyConverter\Provider\Adapter\AdapterInterface;
use Mockery as m;
use CurrencyConverter\Provider\Adapter\CurlAdapter;

class FixerApiTest extends \PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    /**
     * test the default adapter is CurlAdapter
     */
    public function testDefaultAdapter()
    {
        $fixer = new FixerApi();
        $this->assertInstanceOf(CurlAdapter::class, $fixer->getAdapter());
    }

    public function testGetRate()
    {
        $from = 'EUR';
        $to = 'USD';
        $adapter = m::mock(AdapterInterface::class);
        $adapter
            ->shouldReceive('getFile')
            ->once()
            ->with(FixerApi::FIXER_API_BASEPATH . '?symbols=USD&base=EUR')
            ->andReturn('{"base":"EUR","date":"2017-11-02","rates":{"USD":1.1645}}');
        $fixer = new FixerApi();
        $fixer->setAdapter($adapter);
        $this->assertEquals(1.1645, $fixer->getRate($from, $to));
    }
}
