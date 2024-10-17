<?php

namespace App\Models;

use App\Jobs\MarkAsAvailableJob;
use App\Jobs\MarkAsInReviewJob;
use App\Jobs\MarkAsPendingJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'available_date',
        'tax_amount',
        'status',
    ];
    /**
     * Schedule status transitions for a seller withdrawal.
     *
     * @param  \App\Models\WithdrawSchedule  $withdrawal
     * @return void
     */
    // public function schedule(WithdrawSchedule $withdrawal)
    // {
    //     // Schedule the jobs for status transitions
    //     dispatch(new MarkAsInReviewJob($withdrawal))->delay(now()->addDays(3));
    //     dispatch(new MarkAsPendingJob($withdrawal))->delay(now()->addDays(8)); // 5 days after in review
    //     dispatch(new MarkAsAvailableJob($withdrawal))->delay(now()->addDays(15)); // total 15 days

    //     return back()->with('success', 'Withdrawal request submitted.');
    // }
}
