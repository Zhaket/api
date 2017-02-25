<?php
class Zhaket_License
{
	static $check_url = 'http://guard.zhaket.com/api/';
	// Constructor of Zhaket_License class
	public	function	__construct()
	{
		
	}
	//-------------------------------------------------
	// This method sends GET request to specific url and returns the result
	public	static	function	sendRequest($method,$params=array())
	{
		$param_string = http_build_query($params);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, 
			self::$check_url.$method.'?'.$param_string
		);
		$content = curl_exec($ch);
		return json_decode($content);
	}
	//-------------------------------------------------
	public	static	function	isValid($license_token)
	{
		$result = self::sendRequest('validation-license',array('token'=>$license_token,'domain'=>self::getHost()));
		return $result;
	}
	//-------------------------------------------------
	public	static	function	install($license_token,$product_token)
	{
		
		$result = self::sendRequest('install-license',array('product_token'=>$product_token,'token'=>$license_token,'domain'=>self::getHost()));
		return $result;
	}
	//-------------------------------------------------
	public static function getHost() {
		$possibleHostSources = array('HTTP_X_FORWARDED_HOST', 'HTTP_HOST', 'SERVER_NAME', 'SERVER_ADDR');
		$sourceTransformations = array(
			"HTTP_X_FORWARDED_HOST" => function($value) {
				$elements = explode(',', $value);
				return trim(end($elements));
			}
		);
		$host = '';
		foreach ($possibleHostSources as $source)
		{
			if (!empty($host)) break;
			if (empty($_SERVER[$source])) continue;
			$host = $_SERVER[$source];
			if (array_key_exists($source, $sourceTransformations))
			{
				$host = $sourceTransformations[$source]($host);
			} 
		}

		// Remove port number from host
		$host = preg_replace('/:\d+$/', '', $host);
		// remove www from host
		$host = str_ireplace('www.', '', $host);
		
		return trim($host);
	}
	//-------------------------------------------------
	//-------------------------------------------------
	//-------------------------------------------------
	//-------------------------------------------------
	
}
?>