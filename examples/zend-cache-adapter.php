<?php
require '../vendor/autoload.php';

$converter = new CurrencyConverter\CurrencyConverter;

// Via factory:
$zendCache = Zend\Cache\StorageFactory::factory(array(
    'adapter' => array(
        'name'    => 'apc',
        'options' => array('ttl' => 10),
    )
));

$cacheAdapter = new CurrencyConverter\Cache\Adapter\ZendAdapter($zendCache);
$converter->setCacheAdapter($cacheAdapter);
echo $converter->convert('USD', 'NPR');
