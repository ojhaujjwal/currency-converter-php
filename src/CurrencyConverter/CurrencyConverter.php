<?php

namespace CurrencyConverter;

class CurrencyConverter
{
    /**
     * Url where Curl request is made 
     *
     * @var strig
     */
    const API_URl = 'http://download.finance.yahoo.com/d/quotes.csv?s=[fromCurrency][toCurrency]=X&f=nl1d1t1';

	/**
	 * store cache or not
     *
     * @var bool
	 */
	protected $cachable = false;

	/**
	 * The directory where the cache files are stored
     *
     * @var string
	 */
	protected $cacheDirectory = './';

	/**
	 * Length of cache in seconds
     *
     * @var integer
	 */
	protected $cacheTimeout = 18000; // 5 hours

    /**
     * Precision Digits
     *
     * @var integer
     */
    protected $precisionDigits = 3;

    /**
     * Converts currency from one to another
     *
     * @param array $from
     * @param array $to
     * @param float optional $amount
     *
     * @return float|void|integer
     */
    public function convert($from, $to, $amount = 1)
    {
        if (isset($from['country'])) {
            $fromCurrency = CountryToCurrency::getCurrency($from['country']);
        } elseif (isset($from['currency'])) {
            $fromCurrency = $from['currency'];
        } else {
            throw new Exception\InvalidArgumentException('Please provide country or currency under from');
        }

        if (isset($to['country'])) {
            $toCurrency = CountryToCurrency::getCurrency($to['country']);
        } elseif (isset($to['currency'])) {
            $toCurrency = $to['currency'];
        } else {
            throw new Exception\InvalidArgumentException('Please provide country or currency under to');
        }

        $rate = $this->getCache($fromCurrency, $toCurrency);

        if ($rate === false){
            $rate = $this->connect($fromCurrency, $toCurrency);
        }
        
        if ($rate) {
            return round($amount * $rate, $this->getPrecision());
        }
    }

    /**
     * Sets precision
     *
     * @var int $precision
     * @return self
     *
     */
    public function setPrecision($precision)
    {
        $this->precisionDigits = (int) $precision;

        return $this;
    }

    /**
     * Gets precision
     *
     * @return int $precision
     *
     */
    public function getPrecision()
    {
        return $this->precisionDigits;
    }

    /**
     * Sets directory where cache files are created and stored
     *
     * @param string $directory
     * @return self
     * 
     */
    public function setCacheDirectory($directory)
    {
        if ($this->cachable === false) {
            throw new Exception\RunTimeException('Cache is not enabled!');
        } elseif(!is_dir($directory)) {
            throw new Exception\InvalidArgumentException("Directory,$directory does not Exists!");
        }
        $this->cacheDirectory = $directory;

        return $this;
    }

    /**
     * Gets directory where cache files are created and stored
     *
     * @return string 
     * 
     */
    public function getCacheDirectory()
    {
        return $this->cacheDirectory;
    }

    /**
     * Sets if cache is to be created
     * @param boolean $cachable     if true cache is created, else not
     * @return self
     */
    public function setCachable($cachable)
    {
        $this->cachable = (bool) $cachable;

        return $this;
    }

    /*
     *  Sets cache timeout
     *
     *  @param $cacheTimeout Timeout after which cache expires
     *  @returns null
     *  @throws CurrencyConverterException if cache is not enabled
     *
     */
    public function setCacheTimeOut($cacheTimeout)
    {
        if ($this->cachable === false) {
            throw new Exception\RunTimeException('Cache is not enabled!');
        }
        $this->cacheTimeout = $cacheTimeout;

        return $this;
    }


    /**
     *  Gets currently enabled cache timeout
     *  @returns float
     *  @throws CurrencyConverterException if cache is not enabled
     */
    public function getCacheTimeOut()
    {
        if ($this->cachable === false) {
            throw new Exception\RunTimeException('Cache is not enabled!');
        }

        return $this->cacheTimeout;        
    }

    /**
     * Gets cache for currency conversion rates
     *
     * @param string $fromCurrency 
     * @param string $toCurrency
     * @param bool $firstTry
     * @return float|bool
     *  
     */
    protected function getCache($fromCurrency, $toCurrency, $firstTry = true) 
    {
        if (!$this->cachable) {
            return false;
        }
        
        $cacheFile = $this->getCacheFileLocation($fromCurrency, $toCurrency);  
                                            // check if cache is expired
        if (file_exists($cacheFile) and ((time() - filemtime($cacheFile)) < $this->cacheTimeout)) {  
            return file_get_contents($cacheFile);
        } elseif ($firstTry == true ) {
            $reverseConversion = $this->getCache($toCurrency, $fromCurrency, false);
            if($reverseConversion){
                return (1 / $reverseConversion);
            }
        }

        return false;              
    }


	/**
     * Checks if cache is enabled and creates a new file for storing cache 
     *
     * @param float $data
     * @param string $fromCurrency 
     * @param string $toCurrency
     * @return void
	 */
	protected function newCache($data, $fromCurrency, $toCurrency)
	{
		if ($this->cachable) {			
			$cacheFile = $this->getCacheFileLocation($fromCurrency, $toCurrency);
            if (!file_exists($cacheFile)) {
                if (!is_dir(dirname($cacheFile))) {
                    mkdir(dirname($cacheFile), 0755, TRUE);
                }
                touch($cacheFile);
            }
			file_put_contents($cacheFile, $data);			
		}

	}

    /**
     * returns file location for specific currency conversion
     * @param string $fromCurrency 
     * @param string $toCurrency
     */
    protected function getCacheFileLocation($fromCurrency, $toCurrency)
    {
        return $this->cacheDirectory . '/' . $fromCurrency . '-' . $toCurrency . '.cache';
    }

    /**
     * Connects to Yahoo API via Curl and returns rate
     *
     * @param string $fromCurrency 
     * @param string $toCurrency
     * @return int
     * 
     */
    protected function connect($fromCurrency, $toCurrency)
    {
        $fromCurrency = urlencode($fromCurrency);
        $toCurrency = urlencode($toCurrency);
        
        $url = str_replace(
                array('[fromCurrency]', '[toCurrency]'), 
                array($fromCurrency, $toCurrency), 
                static::API_URl
        );
        
        $ch = curl_init();
        $timeout = 0;
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,  CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $rawdata = curl_exec($ch);
        curl_close($ch);
        $rate = explode(',', $rawdata)[1];
        $this->newCache($rate, $fromCurrency, $toCurrency); 

        return $rate;          
    }
}
