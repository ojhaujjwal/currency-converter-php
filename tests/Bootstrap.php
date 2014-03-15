<?php

$files = [
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../../vendor/autoload.php'
];

foreach ($files as $file) {
    if (is_readable($file)) {
        $loader = include $file;
    }
}

if (!isset($loader)) {
    throw new RuntimeException('vendor/autoload.php could not be found. Did you run `php composer.phar install`?');
}
$loader->add('CurrencyConverterTest\\', __DIR__);
