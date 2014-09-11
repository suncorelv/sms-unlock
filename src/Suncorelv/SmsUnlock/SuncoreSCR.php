<?php namespace Suncorelv\SmsUnlock;

/**
 * Suncore.lv SMS Unlock package for Laravel 4
 * Written by ViktorsN (viktors@suncore.lv)
 */

use Config;

class SuncoreSCR {

    public static $prices = array(
        5,7,10,15,20,25,30,35,39,40,
        45,49,50,55,59,60,69,75,79,85,
        89,95,100,125,150,175,200,225,250,275,
        300,310,315,320,325,330,335,340,345,350,
        355,360,365,370,375,380,385,390,395,400,
        405,410,415,420,425,430,435,440,445,450,
        455,460,465,470,475,480,485,490,495,500
    );

    private static $client, $debug;

    public function __construct()
    {
        define( 'SCR_CODE', 1881 );
        define( 'SCR_BASE', 'http://run.suncore.lv/');
        self::$client = Config::get('sms-unlock::client');
        self::$debug  = Config::get('sms-unlock::debug');
    }

    /**
     * Check if price code exists
     * @param $priceCode int :: Price code
     * @return bool
     */
    public function isValidPrice($priceCode)
    {
        return in_array($priceCode, self::$prices);
    }

    /**
     * Convert price to euro
     * @param $price float|integer
     * @param bool $isPriceCode :: true if $price is price code
     * @return float
     */
    public function convertEuro($price, $isPriceCode = false)
    {
        if( $isPriceCode ) $price = number_format( $price / 100, 2, '.', '' );
        return number_format( number_format( $price, 2, '.', '') / 0.702804, 2, '.', ' ' );
    }

    /**
     * @param $key int :: debug or sms code
     * @param $price int :: valid price code
     * @param string $type :: type of payment (sms or paypal)
     * @return string :: Possible responses -> OK, PENDING, FAILED, APISERVER_ERROR, INVALID_PRICE
     */
    public function unlockCheck($key, $price, $type = 'sms')
    {
        if( ! $this->isValidPrice($price) ) return 'INVALID_PRICE';

        // Debug
        $clientIP = $_SERVER['REMOTE_ADDR'];
        if( self::$debug['on'] && self::$debug['key'] === $key && in_array($clientIP, self::$debug['ip']) ) return 'OK';

        // Returns OK, PENDING, FAILED or APISERVER_ERROR, if there was connection error with Suncore API server
        return $this->makeRequest($type, $key, $price) ?: 'APISERVER_ERROR';
    }

    /**
     * @param string $type :: Type of payment (sms or paypal)
     * @param $key int :: Code received from SMS
     * @param $price int :: Suncore price code
     * @return bool|string
     */
    protected function makeRequest($type = 'sms', $key, $price)
    {
        $response = @file_get_contents( SCR_BASE . $type . '/unlock/?key=' . $key . '&client=' . self::$client['id'] . '&price=' . $price . '&apikey=' . self::$client['apikey'], FALSE, NULL, 0, 140);
        if( ! $this->isValidResponse($response, ['OK', 'PENDING', 'FAILED']) ) return false;
        return $response;
    }

    /**
     * Just in_array replacement :D
     * @param $response
     * @param array $responses
     * @return bool
     */
    protected function isValidResponse($response, Array $responses)
    {
        return in_array($response, $responses);
    }

}