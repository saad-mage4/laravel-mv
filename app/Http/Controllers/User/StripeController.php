<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Stripe\{StripeClient, Exception\ApiErrorException};
use Auth;
use Illuminate\Support\Facades\DB;

class StripeController extends Controller
{
    public function StripeController(Request $request)
    {
        $transactionType = $request->value;
        $privateAd = $request->privateAds;
        $stripeValue = "";
        $stripe = new StripeClient(env('STRIPE_SECRET'));

        if ($transactionType == "Public") {
            $stripeValue = env('SPONSOR_PRODUCT_PRICE');
        } elseif ($transactionType == "Private") {
            switch ($privateAd) {
                case "1":
                    $stripeValue = env('SPONSOR_PRODUCT_PRICE_PRIVATE_1');
                    break;
                case "30":
                    $stripeValue = env('SPONSOR_PRODUCT_PRICE_PRIVATE_30');
                    break;
                case "50":
                    $stripeValue = env('SPONSOR_PRODUCT_PRICE_PRIVATE_50');
                    break;
                case "100":
                    $stripeValue = env('SPONSOR_PRODUCT_PRICE_PRIVATE_100');
                    break;
                default:
                    return response()->json(['error' => 'Invalid private ad value'], 400);
            }
        }

        // $transactionType == "Public" ? env('SPONSOR_PRODUCT_PRICE') :
        // env('SPONSOR_PRODUCT_PRICE_PRIVATE')

        try {
            // $transactionType == "Public" ? 'subscription'
            $session = $stripe->checkout->sessions->create([
                'line_items' => [
                    [
                        'price' => $stripeValue,
                        'quantity' => 1,
                    ],
                ],
                'mode' => $transactionType == "Public" ? 'subscription' :  'payment',
                'success_url' => url('/user/membership/subscribe?transaction_type=' . $transactionType . '&status=success&privateAds=' . $privateAd),
                'cancel_url' => url('/user/seller-membership?transaction_type=' . $transactionType),
            ]);
            return $session->url;
        } catch (ApiErrorException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // public function PrivateAdsCron()
    // {
    //     $users = Auth::guard('web')->user();

    //     foreach ($users as $user) {
    //         $adCount = $user->private_ad;

    //         // Check if user exceeds ad limit
    //         $adLimit = $user->ad_limit; // Assuming you store ad limit in user model

    //         if ($adCount > $adLimit) {
    //             // Take action (e.g., disable extra ads, send notification, etc.)
    //             DB::table('products')->where('vendor_id', $user->id)->where('status', '1')
    //                 ->orderBy('created_at', 'desc')
    //                 ->take($adCount - $adLimit)
    //                 ->update(['status' => 'disabled']);

    //             // Optionally, notify the user
    //             // Notification::send($user, new AdLimitExceededNotification());
    //         }
    //     }
    // }
}