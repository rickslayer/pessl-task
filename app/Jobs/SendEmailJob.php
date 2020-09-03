<?php

namespace App\Jobs;


use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Metos\Services\EmailSenderService; 
use Illuminate\Support\Facades\Cache;
use Metos\Services\LogService;

class SendEmailJob extends Job implements ShouldQueue
{
    use SerializesModels;

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
            EmailSenderService::sendEmail($this->to, $this->html);

        } catch (\Throwable $e){
            return array(
                "error" => $e
            );
        }
        LogService::add(
            array(
                "message" => "Email to {$this->to} sent with success",
                "timestamp" => time()
            )
        );
    }
}