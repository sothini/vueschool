<?php

namespace App\Console\Commands;

use App\Services\ApiService;
use Illuminate\Console\Command;

class ProcessBatchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-batch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $service = new ApiService();

        $service->dispatchUserBatchJobs();
        
    }
}
