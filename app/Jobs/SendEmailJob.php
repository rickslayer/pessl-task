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
use Metos\Services\LogService;

class SendEmailJob extends Job implements ShouldQueue
{
    use SerializesModels;

    public $tries = 1;

    public $job;

    private $to;

    private $html;

    public function __construct($to, $html)
    {
        $this->to = $to;
        $this->html = $html;
    }
    /**
     * Main responsable for dispatch asynchronous e-mails 
     */
    public function handle()
    {
        try {
          $emailService = new EmailSenderService();
          $emailService->setTo($this->to);
          $emailService->setHtml($this->html);
          $emailService->sendEmail();

        } catch (\Throwable $e){
            return array(
                "error" => $e
            );
        }
        LogService::add(
            array(
                "message" => "Email to {$this->to}",
                "timestamp" => date('Y-m-d H:i:s')
            )
        );
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