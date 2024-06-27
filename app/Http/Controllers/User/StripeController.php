<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\{StripeClient, Exception\ApiErrorException};

class StripeController extends Controller
{
    public function StripeController(Request $request)
    {
        $transactionType = $request->value;
        $stripe = new StripeClient(env('STRIPE_SECRET'));

        // $transactionType == "Public" ? 'subscription'
        $session = $stripe->checkout->sessions->create([
            'line_items' => [
                [
                    'price' => $transactionType == "Public" ? env('SPONSOR_PRODUCT_PRICE') :
                        env('SPONSOR_PRODUCT_PRICE_PRIVATE'),
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => url('/user/membership/subscribe?transaction_type=' . $transactionType . '&status=success'),
            'cancel_url' => url('/user/seller-membership?transaction_type=' . $transactionType),
        ]);
        return $session->url;
    }
}