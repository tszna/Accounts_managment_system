<?php

namespace App\Jobs;

use App\Repositories\TokenBlacklist\TokenBlacklistRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class ClearTokenBlacklist
 * @package App\Jobs
 */
class ClearTokenBlacklist implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(TokenBlacklistRepository $blacklistRepository)
    {
        $blacklistRepository->deleteObsoleteTokensFromBlacklist();
    }
}
