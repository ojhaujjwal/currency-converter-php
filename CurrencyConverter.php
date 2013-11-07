<?php
namespace library;
require_once(__DIR__."/CountryToCurrency.php");
class CurrencyConverter
{
    /*
    **  url where Curl request is made 
    */
    //const API_URl="http://www.google.com/ig/calculator?hl=en&q=1[fromCurrency]=?[toCurrency]";
    const API_URl = "http://download.finance.yahoo.com/d/quotes.csv?s=[fromCurrency][toCurrency]=X&f=nl1d1t1";


	/*
	* store cache or not
	*/

	private $cachable = FALSE;

	/*
	* The directory where the cache files are stored
	*/

	private $cacheDirectory="./";

	/*
	* Length of cache in seconds
	*/
	private $cacheTimeout=18000; // 5 hours
    
    private $precisionDigits=3;


    protected $from_Currency;

    protected $from_Country;

    protected $to_Currency;

    protected $to_Country;
    
    /*
    **  @param $CountryToCurrency  ---  instance of Class,CountryToCurrency
    */
    protected $CountryToCurrency;


    /*
    **  @function __construct   -- sets from country,from currency, to country, to currency
    **  @param array $from  -- sets "from" data as country or currency
    **  @param array $to  -- sets "to" data as country or currency 
    */
    public function __construct(array $from=array(), array $to=array()) 
    {
        $this->setOptions(array("from"=>$from, "to"=>$to));
    }

    /*
    **  @function setOptions   -- sets from country,from currency, to country, to currency
    **  @param array $options  -- sets "from" and "to" data as country or currency
    */
    public function setOptions(array $options) 
    {
        if (isset($options['from']['currency'])) {
            $this->setFromCurrency($options['from']['currency']);
        } elseif (isset($options['from']['country'])) {
            $this->setFromCountry($options['from']['country']);
        }

        if (isset($options['to']['currency'])) {
            $this->setToCurrency($options['to']['currency']);
        } elseif(isset($options['to']['country'])) {
            $this->setToCountry($options['to']['country']);
        }        
    }
    
    /*
    **  @function getCurrency   --  returns currency code of a country by country code
    **  @param $country_code    --  country code of country whose currency code is to be determined
    */
    private function getCurrency($country_code)
    {
        if(!isset($this->CountryToCurrency)){
            $this->CountryToCurrency=new CountryToCurrency();
        }
        return $this->CountryToCurrency->getCurrency($country_code);
    }

    /*
    **  @function setFromCurrency   --  validates currency code and stores "from currency" in property,$this->from_Currency
    **  @param $from_Currency    -- "from currency"
    */
    public function setFromCurrency($from_Currency)
    {
        try {
            $this->validateCurrency($from_Currency);
        } catch (CurrencyConverterException $e) {
            return FALSE;
        }
        $this->from_Currency=$from_Currency;
    }


    /*
    **  @function setFromCountry   --  sets "from currency code" from country code
    **  @param $from_Country    -- "from country"
    */
    public function setFromCountry($from_Country)
    {
        
        $this->from_Country = $from_Country;
        $from_Currency = $this->getCurrency($from_Country);
        
        $this->setFromCurrency($from_Currency);
    }


    /*
    **  @function setToCurrency   --  validates currency code and stores "to currency" in property,$this->to_Currency
    **  @param $to_Currency    -- "to currency"
    */
    public function setToCurrency($to_Currency)
    {
        try {
            $this->validateCurrency($to_Currency);
        } catch (CurrencyConverterException $e) {
            return FALSE;
        }
        $this->to_Currency=$to_Currency;
    }

    /*
    **  @function setToCountry   --  sets "to currency code" from country code
    **  @param $to_Country    -- "to country"
    */
    public function setToCountry($to_Country)
    {
        $this->to_Country = $to_Country;
        $to_Currency = $this->getCurrency($to_Country);
        $this->setToCurrency($to_Currency);
    }

    /*
    **  @function setCachable -- sets if cache is to be created
    **  @param boolean $cachable    -- if true cache is created, else not
    */
    public function setCachable($cachable)
    {
        $this->cachable = (bool) $cachable;
    }

    /*
    **  @function setCacheTimeOut   --  sets cache timeout
    **  @param $cacheTimeout    --  timeout after which cache expires
    **  @returns null
    **  @throws CurrencyConverterException if cache is not enabled
    */
    public function setCacheTimeOut($cacheTimeout){
        if($this->cachable == FALSE){
            throw new CurrencyConverterException("Cache is not enabled!");
        }
        $this->cacheTimeout=$cacheTimeout;
    }


    /*
    **  @function getCacheTimeOut() -- gets currently enabled cache timeout
    **  @returns cacheTimeout
    **  @throws CurrencyConverterException if cache is not enabled
    */
    public function getCacheTimeOut(){
        if($this->cachable == FALSE){
            throw new CurrencyConverterException("Cache is not enabled!");
        }
        return $this->cacheTimeout=$cacheTimeout;        
    }

    /*
    **  @function setCacheDirectory -- sets path where cache files are created and stored
    */
    public function setCacheDirectory($directory)
    {
        if ($this->cachable == FALSE) {
            throw new CurrencyConverterException("Cache is not enabled!");
        }elseif(!is_dir($directory)){
            throw new CurrencyConverterException("Directory,$directory does not Exists!");
        }
        $this->cacheDirectory = $directory;
    }

    public function setPrecision($precision)
    {
        $this->precisionDigits = $precision;
    }

    public function getPrecision()
    {
        return $this->precisionDigits;
    }

    /*
    **  @function connect   ---     connects to google API via Curl and returns rate
    */
    private function connect($from_Currency, $to_Currency)
    {
        $from_Currency = urlencode($from_Currency);
        $to_Currency = urlencode($to_Currency);
        
        $url = str_replace(
                array("[fromCurrency]","[toCurrency]"), 
                array($from_Currency,$to_Currency), 
                self::API_URl);
        
        $ch = curl_init();
        $timeout = 0;
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch,  CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $rawdata = curl_exec($ch);
        curl_close($ch);
        $rate = explode(",",$rawdata)[1];
        $this->newCache($rate); 
        return $rate;          
    }


    /*
    **  @function getCache
    **      checks if cache file exists 
    *       check if cache is expired
    **      checks for opposite conversion
    **  @param string $from_Currency
    **  @param string $to_Currency
    **  @param bool $first_try -- used for opposite conversion
    */
    private function getCache($from_Currency, $to_Currency, $first_try = TRUE) 
    {
        if(!$this->cachable){
            return FALSE;
        }
        $cacheFile = $this->getCacheFileLocation($from_Currency,$to_Currency);
                                        // check if cache is expired
        if (file_exists($cacheFile) and ((time() - filemtime($cacheFile)) < $this->cacheTimeout)) {  
                     
            return file_get_contents($cacheFile);
        } elseif ($first_try == TRUE ) {
            $reverseConversion = $this->getCache($to_Currency,$from_Currency, FALSE);
           
            if($reverseConversion){
                return (1 / $reverseConversion);
            }
            
        }
        return FALSE;
    }

    /*
    **  @function convert   --  converts currency
    **  @param amount   -- amount to be converted
    */

    public function convert($amount=1)
    {
        $from_Currency = $this->from_Currency;
        
        $to_Currency = $this->to_Currency;
        $rate = $this->getCache($from_Currency, $to_Currency);

        if($rate == FALSE){
            $rate = $this->connect($from_Currency, $to_Currency);
        }
        
        if ($rate) {
            return round($amount * $rate, $this->getPrecision());
        }
    }

	protected function validateCurrency()
	{
		foreach (func_get_args() as $val) {		
			if (strlen($val) !== 3 || !ctype_alpha($val)) {
				if (strtoupper($val) != 'BEAC') {			
					throw new CurrencyConverterException('Invalid currency code - must be exactly 3 letters');					
				}
			}
		}

		return TRUE;	

	}

	/*
    **  @function newCache  -- Checks if cache is enabled and creates a new file for storing cache 
	*/
	private function newCache($data)
	{
		if ($this->cachable) {			
			$cacheFile = $this->getCacheFileLocation();
            if (!file_exists($cacheFile)) {
                if (!is_dir(dirname($cacheFile))) {
                    mkdir(dirname($cacheFile), 0755, TRUE);
                }
                touch($cacheFile);
            }
			file_put_contents($cacheFile, $data);			
		}

	}

    /*
    **  @function getCacheFileLocation  --  returns file location for specific currency conversion
    */
    protected function getCacheFileLocation()
    {
        $num_of_arguements = func_num_args();
        
        if($num_of_arguements!=0 && $num_of_arguements!=2){
            throw new CurrencyConverterException("Invalid number of arguements passed!");
        }
        if($num_of_arguements==0){
            $from_Currency = $this->from_Currency;
            $to_Currency = $this->to_Currency;
        }else{
            $arguements=func_get_args();
            list($from_Currency,$to_Currency)=$arguements;
        }
        return $this->cacheDirectory."/".$from_Currency."-".$to_Currency.".cache";
    }

       
}


class CurrencyConverterException extends \Exception
{
    
}
