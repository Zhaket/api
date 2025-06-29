<?php

class Zhaket_License
{
    public static $api_url = 'guard.zhaket.com/api/';
    public static $api_url2 = 'guard.zhaket.org/api/';

    // Constructor of Zhaket_License class
    public function __construct()
    {

    }
    //-------------------------------------------------
    // This method sends GET request to specific url and returns the result
    public static function send_request($method, $params = array(), $https = false, $server2 = false)
    {
        $param_string = http_build_query($params);
        $protocol = ($https) ? 'https://' : 'http://';

        $api_url =$server2 ? self::$api_url2 : self::$api_url; //check api_url

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $protocol . $api_url . $method . '?' . $param_string );
        curl_setopt($ch, CURLOPT_TIMEOUT, 15); //add timeoute

        $content = curl_exec($ch);
        $error = curl_error($ch);

        if (!empty($error) && $https && !$server2 ){
            return self::send_request($method, $params, false, true);
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode === 0) {
            if ($https) {
                $message = sprintf(__('your server curl has problem, return code:%s, please ticket to host to fix curl problem for url %s', 'zhaket-guard'), $httpCode, $protocol . self::$api_url);
                return json_decode(json_encode(['status' => 'error', 'message' => $message]));
            } else {
                return self::send_request($method, $params, true, $server2);
            }
        }
        return json_decode($content);
    }

    //-------------------------------------------------
    public static function isValid($license_token)
    {
        $result = self::send_request('validation-license', array('token' => $license_token, 'domain' => self::getHost()));
        return $result;
    }

    //-------------------------------------------------
    public static function install($license_token, $product_token)
    {

        $result = self::send_request('install-license', array('product_token' => $product_token, 'token' => $license_token, 'domain' => self::getHost()));
        return $result;
    }

    //-------------------------------------------------
    public static function getHost()
    {
        $domain = get_home_url();
        $result = parse_url($domain);

        if (isset($result['host']) && !empty($result['host'])) {
            return $result['host'];
        }

        $possibleHostSources = array('HTTP_X_FORWARDED_HOST', 'HTTP_HOST', 'SERVER_NAME', 'SERVER_ADDR');
        $sourceTransformations = array(
            "HTTP_X_FORWARDED_HOST" => function ($value) {
                $elements = explode(',', $value);
                return trim(end($elements));
            }
        );

        $host = '';

        foreach ($possibleHostSources as $source) {
            if (!empty($host)) break;
            if (empty($_SERVER[$source])) continue;
            $host = $_SERVER[$source];
            if (array_key_exists($source, $sourceTransformations)) {
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