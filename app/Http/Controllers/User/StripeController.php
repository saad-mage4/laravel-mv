<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Stripe\{StripeClient, Exception\ApiErrorException};
use Carbon\Carbon;
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
        try {
            $session = $stripe->checkout->sessions->create([
                'line_items' => [
                    [
                        'price' => $stripeValue,
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'subscription',
                'success_url' => url('/user/membership/subscribe?transaction_type=' . $transactionType . '&status=success&privateAds=' . $privateAd),
                'cancel_url' => url('/user/seller-membership?transaction_type=' . $transactionType),
            ]);
            return $session->url;
        } catch (ApiErrorException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    //! For Private Monthly Subscribtion && 2 months products status 0
    public function PrivateAdsCron()
    {
        $currentTime = Carbon::now()->format('Y-m-d');
        $users = User::whereNotNull('private_subscription_expiry_date')->get();
        foreach ($users  as $user) {
            $differnce = Carbon::parse($user->private_subscription_expiry_date)->diffInDays($currentTime);
            if ($differnce > 30) {
                $user->is_paid = 0;
                $user->private_ad = 0;
                // $user->private_subscription_expiry_date = null;
                $user->update();
            }
            //! After 2months the products status get 0 (Not shown inthe UI)
            elseif ($differnce > 60) {
                $vendor_id = DB::table('vendors')->select('id')->where('user_id', '=', $user->id)->get()->toArray();
                $vendor_id = implode(array_column($vendor_id, 'id'));
                $products = Product::where('vendor_id', $vendor_id)->get();
                foreach ($products as $product) {
                    $product->status = '0';
                    $product->update();
                }
            }
        }
    }
}