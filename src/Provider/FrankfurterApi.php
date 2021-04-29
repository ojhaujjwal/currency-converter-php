<?php
namespace CurrencyConverter\Provider;

/**
 * Frankfurter currency api
 * https://www.frankfurter.app/
 */
class FrankfurterApi extends AbstractFixerApi
{
    const DEFAULT_BASEPATH = 'api.frankfurter.app/latest';

    /**
     * @param string $basePath
     * @param bool $useHttps
     */
    public function __construct($basePath = self::DEFAULT_BASEPATH, $useHttps = true)
    {
        $this->setBasePath($basePath);
        $this->setUseHttps($useHttps);
    }
}
