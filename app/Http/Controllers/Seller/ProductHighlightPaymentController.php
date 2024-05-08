<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use App\Models\Product;
use Carbon\Carbon;

class ProductHighlightPaymentController extends Controller
{
    public function create(Request $request, $id)
    {
//        dd($request);
        $amount = number_format($request->amount,2);
        $amount = (float)$amount;
        $amountInCents = max(round($amount * 100), 50);
//        dd($amountInCents);


        // Determine the duration based on the amount
        if ($amountInCents === 500.0) {
            $duration = 'One Week';
            $highlightDuration = 1; // Number of weeks for highlight
        } elseif ($amountInCents === 1000.0) {
            $duration = 'Two Weeks';
            $highlightDuration = 2; // Number of weeks for highlight
        } else {
            // Default to 'One Week' if amount is not recognized
            $duration = 'One Week';
            $highlightDuration = 1; // Default to one week
        }



// Set Stripe API key
        Stripe::setApiKey(env('STRIPE_SECRET'));
// Find the product by ID
        $product = Product::find($id);


// Retrieve the payment token from the request
        $token = $request->stripeToken;

// Create a customer with the provided email and payment source
        $customer = Customer::create([
            'email' => $request->email,
            'source' => $token,
        ]);

// Create the charge with the adjusted amount and dynamic description
        $charge = Charge::create([
            'amount' => $amountInCents,
            'currency' => 'eur',
            'customer' => $customer->id,
            'description' => $duration . ' Product Highlight', // Dynamic description
            'metadata' => [
                'product_id' => $product->id
            ]
        ]);

        // Update the product highlight status
            $highlightDate = $highlightDate ?? null;
            $highlightDate = Carbon::now()->addWeeks($highlightDuration);
            $product->highlight_expiry_date = $highlightDate;

        $product->is_highlight_1 = $highlightDuration; // Set is_highlight_1 to duration
//        $product->is_highlight_1 = 1;
        $product->save();

// Redirect or return a response as needed
        return redirect()->back()->with('success', 'Payment successful.');
    }
}
