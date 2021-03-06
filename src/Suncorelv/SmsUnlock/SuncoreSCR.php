<?php namespace Suncorelv\SmsUnlock;

/**
* Suncore.lv SMS Unlock package for Laravel 4
* Written by ViktorsN (viktors@suncore.lv)
*/

use Config, Exception;

class SuncoreSCR {

	private $client, $debug;

	public function __construct()
	{
		define( 'SCR_CODE', 1881 );
		define( 'SCR_BASE', 'http://run.suncore.lv/');
		$this->client = Config::get('sms-unlock::client');
		$this->debug  = Config::get('sms-unlock::debug');
	}

	/**
	* Check if price code exists
	* @param $priceCode int
	* @return bool
	*/
	public function isValidPrice($priceCode)
	{
		return in_array($priceCode, $this->client['prices']);
	}

	/**
	* Convert price to euro
	* @param $price
	* @param bool $isPriceCode
	* @return float
	*/
	public function convertEuro($price, $isPriceCode = false)
	{
		if( $isPriceCode ) $price = number_format( $price / 100, 2, '.', '' );
		return number_format( number_format( $price, 2, '.', '') / 0.702804, 2, '.', ' ' );
	}

	/**
	* @param $key int
	* @param $price int
	* @param string $type
	* @return string :: Possible responses -> OK, PENDING, FAILED, APISERVER_ERROR, INVALID_PRICE
	*/
	public function unlockCheck($key, $price, $type = 'sms')
	{
		if( ! $this->isValidPrice($price) ) return 'INVALID_PRICE';

		// Debug
		$clientIP = $_SERVER['REMOTE_ADDR'];
		if( $this->debug['on'] && $this->debug['key'] === $key && in_array($clientIP, $this->debug['ip']) ) return 'OK';

		// Returns OK, PENDING, FAILED or APISERVER_ERROR, if there was connection error with Suncore API server
		return $this->makeRequest($type, $key, $price);
	}

	/**
	 * @param string $type
	 * @param int $key
	 * @param int $price
	 * @return string
	 * @throws \Exception
	 */
	protected function makeRequest($type = 'sms', $key, $price)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, SCR_BASE . $type . '/unlock/?key=' . $key . '&client=' . $this->client['id'] . '&price=' . $price . '&apikey=' . $this->client['apikey']);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 45);
		$response = curl_exec($curl);

		if(curl_error($curl) || !in_array($response, array('OK', 'PENDING', 'FAILED')))
			throw new Exception('Suncore API server unavailable');

		return $response;
	}

}