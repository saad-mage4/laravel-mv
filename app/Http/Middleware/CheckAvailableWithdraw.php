<?php

namespace App\Http\Middleware;

use App\Models\SellerWithdraw;
use App\Models\WithdrawSchedule;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAvailableWithdraw
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Restrict access if there are no available withdrawals
        $availableWithdraw = WithdrawSchedule::where('status', 'available')->exists();

        if (!$availableWithdraw) {
            $notification = 'You cannot access withdrawals at this time.';
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->route('seller.dashboard')->with($notification);
        }

        return $next($request);
    }
}
