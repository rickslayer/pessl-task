<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
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
       
        while(True) {
            
            $this->info("Starting process get payload");
            $result = PayloadService::PrettyPayload();
            echo json_encode($result, JSON_PRETTY_PRINT);
            sleep(60);
        }
        
        
        
    }
}