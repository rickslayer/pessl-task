<?php

namespace App\Jobs;


use App\Jobs\Job;
use Exception;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Metos\Services\EmailSenderService; 
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Metos\Services\AlertService;
use Metos\Services\LogService;

class ReceivePayloadJob extends Job implements ShouldQueue
{
    use SerializesModels;

    public $tries = 2;

    public $job;

    private $payload;

    public function __construct($payload)
    {
        $this->payload = $payload;
    }
    /**
     * Main responsable for receive payload 
     */
    public function handle()
    {
        try {
            AlertService::sendAlert($this->payload);
            Log::info($this->payload);

        } catch (\Throwable $e){
            return array(
                "error" => $e
            );
        }
       
    }
    /**
     * The job failed to process.
     *
     * @return void
     */
    public function failed($e)
    {
        $info = 'An error has occurred in the class'.class_basename($this).'". Erro: '.$e->getMessage();
        Log::error($info);
    }
}