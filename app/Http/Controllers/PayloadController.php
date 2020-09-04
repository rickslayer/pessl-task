<?php
namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Metos\Services\AlertService;
use Metos\Services\PayloadService;

class PayloadController extends Controller
{
    /**
     * Endpoint responsible for read the Payload 
     * and send alert if is needed
     * 
     * return json
     */
    public function index() : JsonResponse
    {
        $payload = PayloadService::PrettyPayload();
        $alert = AlertService::sendAlert($payload);

         return response()
            ->json([$alert],202);
    }   
}
