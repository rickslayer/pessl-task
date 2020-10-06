<?php

namespace App\Console\Commands;

use App\Jobs\ReceivePayloadJob;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Metos\Services\AlertService;
use Metos\Services\PayloadService;

class CheckPayloadCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:checkPayloadCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Responsable for check payloads';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * Este comando é responsável por atualizar o status da configuração de envio de orçamentos para finalizado
     * @return mixed
     */
    public function handle()
    {
        $qty = 0;
        $payload_frequency_seconds = (int)getenv('SEND_PAYLOAD_FREQUENCY', 15);

        while(true) {    
            $this->info("Starting process get payload");
            
            $payload = PayloadService::PrettyPayload();
            
            dispatch(new ReceivePayloadJob($payload))
                    ->onConnection('redis') 
                    ->onQueue(getenv('REDIS_PAYLOAD_QUEUE'));
            
            $this->info(json_encode($payload, JSON_PRETTY_PRINT));
            $this->info("Finishing process get payload");
            

            if($qty == $payload['payload_qty']) {
                break;
            }
            $qty++;
            
            sleep($payload_frequency_seconds);
        }

        $this->info("Finish");
    }
}