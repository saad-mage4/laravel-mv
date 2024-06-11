<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Sponsorships;
use Carbon\Carbon;
use Illuminate\Contracts\{Foundation\Application, View\Factory, View\View};
use Illuminate\Database\{Eloquent\Model, Query\Builder};
use Illuminate\Routing\Redirector;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\{Auth, DB};
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class SellerSponsorController extends Controller
{
    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function getSponsorPayment(Request $request)
    {
        if ($request->has('transaction_type') && request('transaction_type') == 'BuySponsorship') {
            $position = $request->banner_position;
            $current_user = Auth::user()->getAuthIdentifier();
            $user = $request->user_id;

            if ($current_user == (int)$user) {
                DB::table('sponsorships')->where('banner_position', $position)
                    ->update(['is_booked' => '1', 'activation_date' => Carbon::now()->format('Y-m-d H:i:s')]);
            }
        }

        $notification = 'Payment Success! Banner has been uploaded successfully!';
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect('seller/show-sponsor')->with($notification);
    }

    /**
     * @return Application|Factory|View
     */
    public function showSponsor()
    {
        $banners = DB::table('sponsorships')->get();
        $userID = Auth::user()->getAuthIdentifier();
        return view('seller.show_sponsor', compact('banners', 'userID'));
    }

    /**
     * @return Application|Factory|View
     */
    public function frontShowSponsor()
    {
        $banners = DB::table('sponsorships')->get();
        return view('sponsor', compact('banners'));
    }

    /**
     * @param Request $request
     * @return Model|Builder|object|null
     */
    public function getSponsor(Request $request)
    {
        return DB::table('sponsorships')->where('banner_position', $request->position)->first();
    }

    /**
     * @param Request $request
     * @return string
     * @throws ApiErrorException
     */
    public function addSponsorReq(Request $request): string
    {
        $imagePath = null;
        // Check if an image is uploaded
        if ($request->hasFile('banner_img')) {
            $image = $request->file('banner_img');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/sponsors'), $imageName);
            $imagePath = 'images/sponsors/' . $imageName;
        }
        $userId = Auth::user()->getAuthIdentifier();
        $sponsorship = new Sponsorships();

        $banner = DB::table('sponsorships')->where('banner_position', $request->image_position)->first();

        if (!empty($banner)) {
            if ($userId == (int)$banner->sponsor_user_id) {
                $sponsorship->updateSponsor($request, $imagePath);
                return redirect()->back()->with(['messege' => 'Updated Successfully!', 'alert-type' => 'success']);
            }
        } else {
            $sponsorship->addSponsor($request, $imagePath);
        }

        $transactionType = 'BuySponsorship';
        $stripe = new StripeClient(env('STRIPE_SECRET'));

        $session = $stripe->checkout->sessions->create([
            'line_items' => [
                [
                    'price' => env('SPONSOR_PRODUCT_PRICE'),
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => url('/seller/payment-success?session_id={CHECKOUT_SESSION_ID}&user_id=' . $userId . '&transaction_type=' . $transactionType . '&banner_position=' . $request->image_position),
            'cancel_url' => url('/seller'),
        ]);

        return redirect($session->url);
    }

    /**
     * @return void
     */
    public function bannerRemoveCron(): void {
        $banners = DB::table('sponsorships')->get();
        if (!empty($banners)) {
            foreach ($banners as $banner) {
                $days = $banner->activation_date ?? null;
                $currentDate = Carbon::now();
                $diffInDays = $banner ? $currentDate->diffInDays($days) : null;
                if ($diffInDays >= 15) {
                    DB::table('sponsorships')->where('banner_position', $banner->banner_position)->delete();
                }
            }
        }
    }
}
