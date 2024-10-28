<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class UpdateUserJob //implements ShouldQueue
{
    use Queueable;
    private $data;
    /**
     * Create a new job instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $payload = [
            "batches" => [
                [
                    "subscribers" => $this->data
                ]
            ]
        ];

        Log::info(json_encode($payload));
        dump('done');
    }
}
