<?php
namespace Metos\Services;

use App\Jobs\SendEmailJob;
use Illuminate\Support\Facades\Cache;
/**
 * class responsible for checking the need to send an alert 
 */
class AlertService
{
    /**
     * Method responsible for defining the need to send
     * the alert and check the parameters
     * 
     * @param array $payload - the payload data plus e-mail
     */
    public static function sendAlert($payload) : array
    {
        $parameters = $payload['data'];
        $email = $payload['email'];
        $result = [];
        $messages = [];
        $timestamp = date('Y-m-d H:i:s');
        /**
         * Recovers data that has been saved in the Cache by the user. 
         * If it does not exist, search for data on environment variables
         */
        $cached_params = Cache::store("redis")->get("{$email}_data");
        $user_parameters = [
            "battery" => $cached_params['battery'] ?? getenv("PARAMETER_BATERY_MIN"),
            "humidity" => $cached_params['rh'] ??  getenv("PARAMETER_RELATIVE_HUMIDITY_MAX"),
            "air_temperature" => $cached_params['air'] ??  getenv("PARAMETER_AIR_TEMPERATURE_MIN"),
            "dew_point" =>  $cached_params['dw'] ?? getenv("PARAMETER_DEW_POINT_MIN"),
        ];    
        
        if (round($parameters['battery']) <= $user_parameters['battery']) {
            $messages[] = "<p>Caution: Battery it's lower or equal than parameter: {$user_parameters['battery']} Given: {$parameters['battery']} -  DateTime: {$timestamp}</p></p>";
        }

        if (round($parameters['rh_mx']) >= $user_parameters['humidity']) {
            $messages[] = "<p>Caution: Relative Humidity it's higher or equal than parameter: {$user_parameters['humidity']} Given: {$parameters['rh_mx']} - DateTime: {$timestamp}</p>";
        }

        if (round($parameters['air_mn']) <= $user_parameters['air_temperature']) {
            $messages[] = "<p>Caution: Air Temperature it's lower or equal than parameter: {$user_parameters['air_temperature']} - Given: {$parameters['air_mn']} DateTime: {$timestamp}</p>";
        }

        if (round($parameters['dew_mn']) <= $user_parameters['dew_point']) {
            $messages[] = "<p>Caution: Dew Point it's lower or equal than parameter: {$user_parameters['dew_point']} - Given: {$parameters['dew_mn']} DateTime: {$timestamp}</p>";
        }

        /**
         * Check if the parameters are not good
         */
        if (count($messages) > 0) {
            $sender = EmailSenderService::checkEmailFrequence($email, $messages);
            /**
             * Check if needs to send an e-mail. Waiting 15 min to send another
             * Dispatch to the queue
             */
           if($sender['need_to_send']) {
                $enqued_email = dispatch(new SendEmailJob($email, $sender['html']))
                                    ->onConnection('redis')
                                    ->onQueue(getenv('REDIS_QUEUE_NAME'));
                if ($enqued_email) {
                    $result['message'] = "Message send to the queue";
                }
            } else {
                $result['message'] = "Message storage waiting to send";
            }
        } else {
            $result = array (
                "message" => "There are no alerts to send",
                "send" => false
            );
        }
        return $result;
    }
}
