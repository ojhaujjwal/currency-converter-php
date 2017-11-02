<?php
namespace CurrencyConverter\Provider\Adapter;

/**
 * Fetch files with cUrl
 *
 * @package CurrencyConverter\Provider\Adapter
 */
class CurlAdapter implements AdapterInterface
{
    public function getFile($pathOrUrl)
    {
        $ch = curl_init();
        $timeout = 0;
        curl_setopt($ch, CURLOPT_URL, $pathOrUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $rawdata = curl_exec($ch);
        curl_close($ch);

        return $rawdata;
    }
}