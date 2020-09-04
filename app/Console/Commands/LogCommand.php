<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Metos\Services\LogService;

class LogCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Responsable for show logs';

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
            
            $this->info("Starting process get logs");
            $result = LogService::get();
            if($result != null){ 
                $this->info($result);
            }else {
                $this->info('no data found');
            }
            
            sleep(3);
          
        }
        
    }
}