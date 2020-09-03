<?php
namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Metos\Helpers\DataParser; 
use Metos\Services\EmailSender;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Metos\Services\AlertService;
use Metos\Services\LogService;
use Metos\Services\PayloadService;

class PayloadController extends Controller
{
    public function index() : JsonResponse
    {
         $payload = PayloadService::PrettyPayload();
         
         $confirm_alert = AlertService::sendAlert($payload);

         if($confirm_alert['send']) {
            $result = EmailSender::checkEmailFrequence($payload['email'], $confirm_alert['html']);

            EmailSender::sendEmail($payload['email'], $confirm_alert['html']);

            if($result['need_to_send']) {
                $this->dispatch(new SendEmailJob($payload['email'], $result['html']));
            }
         }
 
         
        return response()
        ->json($payload);
    }

   
}