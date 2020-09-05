<?php
namespace App\Http\Controllers;

use App\Jobs\ReceivePayloadJob;
use Exception;
use Illuminate\Http\JsonResponse;
use Metos\Services\AlertService;
use Metos\Services\PayloadService;

/**
 * Endpoint to process payload
 */
class PayloadController extends Controller
{
    /**
     * Endpoint responsible for read the Payload 
     * and send to queue
     * 
     * @return Illuminate\Http\JsonResponse
     */
    public function index() : JsonResponse
    {
        $result = array();
        try{
            $payload = PayloadService::PrettyPayload();
        
            $enqueued = dispatch(new ReceivePayloadJob($payload))
                    ->onConnection('redis') 
                    ->onQueue(getenv('REDIS_PAYLOAD_QUEUE'));
            
            if($enqueued) {
                $result['payload'] = $payload;
                $result['enqueued'] = true;
                $result['message'] = "processed payload with success";
            }

        } catch(Exception $e) {
            throw $e;
        }
        
         return response()
            ->json([$result],202);
    }   
}
