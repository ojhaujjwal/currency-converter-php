<?php
require '../vendor/autoload.php';

$converter = new CurrencyConverter\CurrencyConverter;

// Via factory:
$zendCache = Zend\Cache\StorageFactory::factory(array(
    'adapter' => array(
        'name'    => 'Filesystem',
        'options' => array('ttl' => 10, 'cache_dir' => __DIR__ . '/cache'),
    )
));

$cacheAdapter = new CurrencyConverter\Cache\Adapter\ZendAdapter($zendCache);
$converter->setCacheAdapter($cacheAdapter);
echo $converter->convert('USD', 'NPR');
