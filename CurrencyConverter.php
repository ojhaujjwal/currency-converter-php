<?php
namespace library;

class CurrencyConverter
{


    /*
    **  url where Curl request is made 
    */
    const GOOGLE_API_URl="http://www.google.com/ig/calculator?hl=en&q=1[fromCurrency]=?[toCurrency]";


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
    **  @param array $currencies  --  array of currencies
    **      key contains country code
    **      value contains respective country currency
    */
    private $currencies = array(
					'AF' => 'AFA',
					'AL' => 'ALL',
					'DZ' => 'DZD',
					'AS' => 'USD',
					'AD' => 'EUR',
					'AO' => 'AOA',
					'AI' => 'XCD',
					'AQ' => 'NOK',
					'AG' => 'XCD',
					'AR' => 'ARA',
					'AM' => 'AMD',
					'AW' => 'AWG',
					'AU' => 'AUD',
					'AT' => 'EUR',
					'AZ' => 'AZM',
					'BS' => 'BSD',
					'BH' => 'BHD',
					'BD' => 'BDT',
					'BB' => 'BBD',
					'BY' => 'BYR',
					'BE' => 'EUR',
					'BZ' => 'BZD',
					'BJ' => 'XAF',
					'BM' => 'BMD',
					'BT' => 'BTN',
					'BO' => 'BOB',
					'BA' => 'BAM',
					'BW' => 'BWP',
					'BV' => 'NOK',
					'BR' => 'BRL',
					'IO' => 'GBP',
					'BN' => 'BND',
					'BG' => 'BGL',
					'BF' => 'XAF',
					'BI' => 'BIF',
					'KH' => 'KHR',
					'CM' => 'XAF',
					'CA' => 'CAD',
					'CV' => 'CVE',
					'KY' => 'KYD',
					'CF' => 'XAF',
					'TD' => 'XAF',
					'CL' => 'CLF',
					'CN' => 'CNY',
					'CX' => 'AUD',
					'CC' => 'AUD',
					'CO' => 'COP',
					'KM' => 'KMF',
					'CD' => 'CDZ',
					'CG' => 'XAF',
					'CK' => 'NZD',
					'CR' => 'CRC',
					'HR' => 'HRK',
					'CU' => 'CUP',
					'CY' => 'EUR',
					'CZ' => 'CZK',
					'DK' => 'DKK',
					'DJ' => 'DJF',
					'DM' => 'XCD',
					'DO' => 'DOP',
					'TP' => 'TPE',
					'EC' => 'USD',
					'EG' => 'EGP',
					'SV' => 'USD',
					'GQ' => 'XAF',
					'ER' => 'ERN',
					'EE' => 'EEK',
					'ET' => 'ETB',
					'FK' => 'FKP',
					'FO' => 'DKK',
					'FJ' => 'FJD',
					'FI' => 'EUR',
					'FR' => 'EUR',
					'FX' => 'EUR',
					'GF' => 'EUR',
					'PF' => 'XPF',
					'TF' => 'EUR',
					'GA' => 'XAF',
					'GM' => 'GMD',
					'GE' => 'GEL',
					'DE' => 'EUR',
					'GH' => 'GHC',
					'GI' => 'GIP',
					'GR' => 'EUR',
					'GL' => 'DKK',
					'GD' => 'XCD',
					'GP' => 'EUR',
					'GU' => 'USD',
					'GT' => 'GTQ',
					'GN' => 'GNS',
					'GW' => 'GWP',
					'GY' => 'GYD',
					'HT' => 'HTG',
					'HM' => 'AUD',
					'VA' => 'EUR',
					'HN' => 'HNL',
					'HK' => 'HKD',
					'HU' => 'HUF',
					'IS' => 'ISK',
					'IN' => 'INR',
					'ID' => 'IDR',
					'IR' => 'IRR',
					'IQ' => 'IQD',
					'IE' => 'EUR',
					'IL' => 'ILS',
					'IT' => 'EUR',
					'CI' => 'XAF',
					'JM' => 'JMD',
					'JP' => 'JPY',
					'JO' => 'JOD',
					'KZ' => 'KZT',
					'KE' => 'KES',
					'KI' => 'AUD',
					'KP' => 'KPW',
					'KR' => 'KRW',
					'KW' => 'KWD',
					'KG' => 'KGS',
					'LA' => 'LAK',
					'LV' => 'LVL',
					'LB' => 'LBP',
					'LS' => 'LSL',
					'LR' => 'LRD',
					'LY' => 'LYD',
					'LI' => 'CHF',
					'LT' => 'LTL',
					'LU' => 'EUR',
					'MO' => 'MOP',
					'MK' => 'MKD',
					'MG' => 'MGF',
					'MW' => 'MWK',
					'MY' => 'MYR',
					'MV' => 'MVR',
					'ML' => 'XAF',
					'MT' => 'EUR',
					'MH' => 'USD',
					'MQ' => 'EUR',
					'MR' => 'MRO',
					'MU' => 'MUR',
					'YT' => 'EUR',
					'MX' => 'MXN',
					'FM' => 'USD',
					'MD' => 'MDL',
					'MC' => 'EUR',
					'MN' => 'MNT',
					'MS' => 'XCD',
					'MA' => 'MAD',
					'MZ' => 'MZM',
					'MM' => 'MMK',
					'NA' => 'NAD',
					'NR' => 'AUD',
					'NP' => 'NPR',
					'NL' => 'EUR',
					'AN' => 'ANG',
					'NC' => 'XPF',
					'NZ' => 'NZD',
					'NI' => 'NIC',
					'NE' => 'XOF',
					'NG' => 'NGN',
					'NU' => 'NZD',
					'NF' => 'AUD',
					'MP' => 'USD',
					'NO' => 'NOK',
					'OM' => 'OMR',
					'PK' => 'PKR',
					'PW' => 'USD',
					'PA' => 'PAB',
					'PG' => 'PGK',
					'PY' => 'PYG',
					'PE' => 'PEI',
					'PH' => 'PHP',
					'PN' => 'NZD',
					'PL' => 'PLN',
					'PT' => 'EUR',
					'PR' => 'USD',
					'QA' => 'QAR',
					'RE' => 'EUR',
					'RO' => 'ROL',
					'RU' => 'RUB',
					'RW' => 'RWF',
					'KN' => 'XCD',
					'LC' => 'XCD',
					'VC' => 'XCD',
					'WS' => 'WST',
					'SM' => 'EUR',
					'ST' => 'STD',
					'SA' => 'SAR',
					'SN' => 'XOF',
					'CS' => 'EUR',
					'SC' => 'SCR',
					'SL' => 'SLL',
					'SG' => 'SGD',
					'SK' => 'EUR',
					'SI' => 'EUR',
					'SB' => 'SBD',
					'SO' => 'SOS',
					'ZA' => 'ZAR',
					'GS' => 'GBP',
					'ES' => 'EUR',
					'LK' => 'LKR',
					'SH' => 'SHP',
					'PM' => 'EUR',
					'SD' => 'SDG',
					'SR' => 'SRG',
					'SJ' => 'NOK',
					'SZ' => 'SZL',
					'SE' => 'SEK',
					'CH' => 'CHF',
					'SY' => 'SYP',
					'TW' => 'TWD',
					'TJ' => 'TJR',
					'TZ' => 'TZS',
					'TH' => 'THB',
					'TG' => 'XAF',
					'TK' => 'NZD',
					'TO' => 'TOP',
					'TT' => 'TTD',
					'TN' => 'TND',
					'TR' => 'TRY',
					'TM' => 'TMM',
					'TC' => 'USD',
					'TV' => 'AUD',
					'UG' => 'UGS',
					'UA' => 'UAH',
					'SU' => 'SUR',
					'AE' => 'AED',
					'GB' => 'GBP',
					'US' => 'USD',
					'UM' => 'USD',
					'UY' => 'UYU',
					'UZ' => 'UZS',
					'VU' => 'VUV',
					'VE' => 'VEF',
					'VN' => 'VND',
					'VG' => 'USD',
					'VI' => 'USD',
					'WF' => 'XPF',
					'XO' => 'XOF',
					'EH' => 'MAD',
					'ZM' => 'ZMK',
					'ZW' => 'USD'
    );


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
        return $this->currencies[$country_code];
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
                self::GOOGLE_API_URl);
        
        $ch = curl_init();
        $timeout = 0;
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch,  CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $rawdata = curl_exec($ch);
        curl_close($ch);

        $data = explode('"', $rawdata);
        $data = explode(' ', $data['3']);
        $rate = $data['0'];
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
