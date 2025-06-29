<?php

class WeatherAPI 
{
    private $api_key;
    private $base_url;

    public function __construct() 
    {
        $config = require __DIR__ . '/../../config/config.php';
        $this->api_key = $config['weather']['api_key'];
        $this->base_url = $config['weather']['base_url'];
    }

    public function getWeather($city) 
    {
        $url = "{$this->base_url}?units=metric&APPID={$this->api_key}&q=" . urlencode($city) . "&lang=id";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        
        if (curl_errno($ch)) {
            throw new Exception('cURL Error: ' . curl_error($ch));
        }
        
        curl_close($ch);

        return json_decode($result, true);
    }
}
