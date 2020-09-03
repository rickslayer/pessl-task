<?php
namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use Illuminate\Http\JsonResponse;
use Metos\Services\EmailSender;
use Metos\Services\AlertService;
use Metos\Services\PayloadService;

class PayloadController extends Controller
{
    /**
     * Endpoint responsible for read the Payload 
     * and send email asynchronous if it's necessary
     * 
     * return json
     */
    public function index() : JsonResponse
    {
        $result = array();
        $payload = PayloadService::PrettyPayload();

        $confirm_alert = AlertService::sendAlert($payload);

        /**
         * Check if the parameters are not good
         */
        if($confirm_alert['send']) {
            $sender = EmailSender::checkEmailFrequence($payload['email'], $confirm_alert['html']);
            /**
             * Check if needs to send an e-mail. Waiting 15 min to send another
             * Dispatch to the queue
             */
           if($sender['need_to_send']) {
                $enqued_email = $this->dispatch(new SendEmailJob($payload['email'], $sender['html']));
                if ($enqued_email) {
                    $result['message'] = "Message send to the queue";
                    $result['queue_id'] = $enqued_email;
                }
            } else {
                $result['message'] = "Message storage waiting 15 min to send";
            }

        } else {
            $result['message'] = "No need Alert";
         }
 
         return response()
         ->json([$result],202);
    }   
}
