<?php

namespace App\Jobs;

use App\Models\SellerWithdraw;
use App\Models\WithdrawSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MarkAsAvailableJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $withdrawal;

    public function __construct(WithdrawSchedule $withdrawal)
    {
        $this->withdrawal = $withdrawal;
    }

    public function handle()
    {
        // Update the withdrawal status to "available"
        $this->withdrawal->update([
            'status' => 'available',
            'available_date' => now(),
        ]);
    }
}
