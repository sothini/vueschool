<?php

namespace App\Services;

use App\Jobs\UpdateUserJob;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ApiService
{
    protected int $batchSize;
    protected int $batchesPerHour;
    protected string $cacheKey = 'user_batch';

    public function __construct()
    {
        // Configure batch settings from environment variables
        $this->batchSize = env('USER_BATCH_SIZE', 1000);
        $this->batchesPerHour = env('BATCHES_PER_HOUR', 50);
    }


    public function captureUser(User $user): void
    {
        $data = Cache::get($this->cacheKey, []);
        $data[] = $user->toArray();

        Cache::put($this->cacheKey, $data);
    }

    public function dispatchUserBatchJobs(): void
    {
        $data = Cache::get($this->cacheKey, []);
        $processed_batches = 0;
        $processor = [];

        while (!empty($data) && $processed_batches < $this->batchesPerHour) {

            $item = array_shift($data); //remove the item and the processing
            $processor[] = $item;

            // Dispatch job when batch size is reached
            if (count($processor) >= $this->batchSize) {
                $this->dispatchJob($processor);
                $processor = [];
                $processed_batches++;
            }
        }

        // Dispatch any remaining items that didn’t reach batch size
        if (count($processor) > 0) {
            $this->dispatchJob($processor);
        }

        // Update cache with any unprocessed data
        Cache::put($this->cacheKey, $data);
    }

    protected function dispatchJob(array $batch): void
    {
        try {
            UpdateUserJob::dispatch($batch);
        } catch (\Exception $e) {
            Log::error("Failed to dispatch UpdateUserJob: " . $e->getMessage());
        }
    }
}
