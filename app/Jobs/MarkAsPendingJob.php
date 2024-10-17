<?php

namespace App\Jobs;

use App\Models\SellerWithdraw;
use App\Models\WithdrawSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MarkAsPendingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $withdrawal;

    public function __construct(WithdrawSchedule $withdrawal)
    {
        $this->withdrawal = $withdrawal;
    }

    public function handle()
    {
        // Update the withdrawal status to "pending"
        $this->withdrawal->update([
            'status' => 'pending',
            'pending_date' => now(),
        ]);
    }
}
