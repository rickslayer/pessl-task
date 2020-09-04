<?php

namespace App\Console\Commands;

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
        while(true) {    
            $this->info("Starting process get payload");
            $payload = PayloadService::PrettyPayload();
            $alert = AlertService::sendAlert($payload);
            $this->info(json_encode($alert, JSON_PRETTY_PRINT));
            sleep(3);
            $qty++;

            if($qty == $payload['payload_qty']){
               break;
            }
        }
        $this->info("Finish");
    }
}