<?php
namespace CurrencyConverter\Provider\Adapter;

/**
 * Adapter Interface to fetch files from APIs
 *
 * @package CurrencyConverter\Provider\Adapter
 */
interface AdapterInterface
{
    /**
     * Fetches file from path or url, returns raw string contents
     *
     * @param string $pathOrUrl
     * @return string
     */
    public function getFile($pathOrUrl);
}