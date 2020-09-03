<?php
namespace Metos\Services;

use Illuminate\Support\Facades\Cache;

class AlertService
{
    public static function sendAlert($payload)
    {
        $parameters = $payload['data'];
        $email = $payload['email'];
        $result = [];
        $messages = [];
        $timestamp = date('Y-m-d H:i:s');
        $cached_params = Cache::store("redis")->get("{$email}_data");
        if ($cached_params != null) {
            $user_parameters = [
                "battery" => $cached_params['battery'] ?? getenv("PARAMETER_BATERY_MIN"),
                "humidity" => $cached_params['rh'] ??  getenv("PARAMETER_RELATIVE_HUMIDITY_MAX"),
                "air_temperature" => $cached_params['air'] ??  getenv("PARAMETER_AIR_TEMPERATURE_MIN"),
                "dew_point" =>  $cached_params['dw'] ?? getenv("PARAMETER_DEW_POINT_MIN"),
            ];    
        }

        
        if (round($parameters['battery']) < $user_parameters['battery']) {
            $messages[] = "<p>Caution: Battery it's lower than parameter: {$user_parameters['battery']} Given: {$parameters['battery']} -  DateTime: {$timestamp}</p></p>";
        }

        if (round($parameters['rh_mx']) > $user_parameters['humidity']) {
            $messages[] = "<p>Caution: Relative Humidity it's higher than parameter: {$user_parameters['humidity']} Given: {$parameters['rh_mx']} - DateTime: {$timestamp}</p>";
        }

        if (round($parameters['air_mn']) < $user_parameters['air_temperature']) {
            $messages[] = "<p>Caution: Air Temperature it's lower than parameter: {$user_parameters['air_temperature']} - Given: {$parameters['air_mn']} DateTime: {$timestamp}</p>";
        }

        if (round($parameters['dew_mn']) < $user_parameters['dew_point']) {
            $messages[] = "<p>Caution: Dew Point it's lower than parameter: {$user_parameters['dew_point']} - Given: {$parameters['dew_mn']} DateTime: {$timestamp}</p>";
        }
        if (count($messages) > 0) {
            $result = array (
                "message" => "There are alerts to send",
                "html" => $messages,
                "send" => true
            );
        } else {
            $result = array (
                "message" => "There are no alerts to send",
                "send" => false
            );
        }

        return $result;

      
    }
}
