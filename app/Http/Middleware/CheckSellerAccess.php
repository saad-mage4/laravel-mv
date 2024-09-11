<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use App\Models\Vendor;
use Closure;
use Auth;
use Carbon\Carbon;

class CheckSellerAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    // Defult for test route
    // public function handle($request, Closure $next)
    // {
    //     $authUser = Auth::guard('web')->user();
    //     $isSeller = Vendor::where('user_id', $authUser->id)->first();
    //     $Check_Status = $isSeller->status ?? 1;

    //     if (
    //         ($authUser->is_member == true && $authUser->seller_type == "Private" && $authUser->is_paid == 0 && $isSeller == true && $authUser->private_ad == 0)
    //         || ($authUser->is_member == true && $Check_Status == 1)
    //     ) {
    //         return $next($request); // Continue if conditions match
    //     }

    //     return redirect()->route('home')->with('error', 'You do not have access to this page.');
    // }

    // Now for both Seller membership & test route
    public function handle($request, Closure $next)
    {
        // Get the authenticated user
        $user = Auth::guard('web')->user();

        // Calculate current time and date differences for private and public subscriptions
        $currentTime = Carbon::now()->format('Y-m-d');
        $privateDifference = Carbon::parse($user->private_subscription_expiry_date)->diffInDays($currentTime);
        $publicDifference = Carbon::parse($user->subscription_expiry_date)->diffInDays($currentTime);

        // Handle private seller access
        if ($user->is_member == 1 && $user->seller_type == "Private") {
            if ($privateDifference > 30) {
                $notification = 'Your monthly ads time has been ended';
            } elseif ($user->is_paid == 0 && $user->private_ad == 0) {
                $notification = 'Your Ads Limit Reached!';
            }
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect('user/test')->with($notification); // Redirect to test view
        }

        // Handle public seller access
        if ($user->is_member == 0 && $user->is_paid == 1 && $user->seller_type == "Public") {
            $notification = 'You have already Paid!';
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect('user/seller-registration')->with($notification); // Redirect to seller registration
        }

        // Handle public seller expiration case
        if ($user->is_member == 1 && $user->is_paid == 0 && $user->seller_type == "Public" && $publicDifference > 30) {
            // Continue with the request, allowing access
            return $next($request);
        }

        // Default access if none of the conditions match
        return $next($request);
    }
}