<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Sponsorships;
use Carbon\Carbon;
use Illuminate\Contracts\{Foundation\Application, View\Factory, View\View};
use Illuminate\Database\{Eloquent\Model, Query\Builder};
use Illuminate\Routing\Redirector;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\{Auth, DB, Validator};
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
        $notification = null;
        if ($request->has('transaction_type') && $request->transaction_type == 'BuySponsorship') {
            $banner = json_decode($request->banner, true);
            $position = $banner['image_position'];
            $current_user = Auth::user()->getAuthIdentifier();
            $user = $request->user_id;

            if ($current_user == (int)$user) {
                DB::table('sponsorships')
                    ->where(['banner_position' => $position, 'sponsor_user_id' => $user])
                    ->update([
                        'is_booked' => '1',
                        'status' => 'active',
                        'activation_date' => Carbon::now()->format('Y-m-d H:i:s'),
                    ]);
            }
            $notification = 'Payment Success! Banner has been uploaded successfully!';
        }

        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect('seller/show-sponsor')->with($notification);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function showSponsor(Request $request)
    {
        $userID = Auth::user()->getAuthIdentifier();
        if ($request->has('transaction_type') && $request->transaction_type == 'BuySponsorship') {
            $banner = json_decode($request->banner, true);
            $position = $banner['image_position'];
            $user = $request->user_id;

            if ($userID == (int)$user) {
                DB::table('sponsorships')->where(['banner_position' => $position, 'sponsor_user_id' => $user])->delete();
            }
        }
        $banners = DB::table('sponsorships')->get();
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
        $sponsorship = new Sponsorships();

        // Validate the request inputs
        $validator = Validator::make($request->all(), [
            'banner_img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'prod_link' => 'required|url|regex:/^https:\/\/.*/',
            'sponsor_name' => 'nullable|string|max:255',
            'image_position' => 'required|string|max:255',
        ], [
            'prod_link.regex' => 'The product link must start with "https://".',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $imagePath = null;
        if ($request->hasFile('banner_img')) {
            $image = $request->file('banner_img');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            $details = $sponsorship->getBannerDetails($request->image_position);

            list($width, $height) = getimagesize($image->getRealPath());

            if ($width != $details['width'] || $height != $details['height']) {
                return redirect()->back()->with(['messege' => 'Image dimensions must be . ' . $details['width'] . 'x' . $details['height'], 'alert-type' => 'error'])->withInput();
            }
            $image->move(public_path('images/sponsors'), $imageName);
            $imagePath = 'images/sponsors/' . $imageName;
        }

        $userId = Auth::user()->getAuthIdentifier();

        $banner = DB::table('sponsorships')->where('banner_position', $request->image_position)->first();
        if (!empty($banner)) {
            if ($userId == (int)$banner->sponsor_user_id && $banner->is_booked == '1' && $banner->status == 'active') {
                $sponsorship->updateSponsor($request, $imagePath);
                return redirect()->back()->with(['messege' => 'Updated Successfully!', 'alert-type' => 'success']);
            } elseif ($banner->status == 'in-progress' && $userId != (int)$banner->sponsor_user_id) {
                return redirect()->back()->with(['messege' => 'This slot is hold by another person', 'alert-type' => 'warning']);
            }
        }
        $sponsorship->addSponsor($request, $imagePath);

        // Prepare banner details for the success URL
        $bannerDetails = [
            'image_position' => $request->image_position,
            'prod_link' => $request->prod_link,
            'sponsor_name' => $request->sponsor_name,
            'image_url' => $imagePath,
        ];

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
            'success_url' => url('/seller/payment-success?user_id=' . $userId . '&transaction_type=' . $transactionType . '&banner=' . urlencode(json_encode($bannerDetails))),
            'cancel_url' => url('/seller/show-sponsor?user_id=' . $userId . '&transaction_type=' . $transactionType . '&banner=' . urlencode(json_encode($bannerDetails))),
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

                $checkInProgress = $banner->updated_at ?? null;
                $diffInMints = $banner ? $currentDate->diffInMinutes($checkInProgress) : null;
                if ($diffInDays >= 15) {
                    DB::table('sponsorships')->where(['banner_position', $banner->banner_position])->delete();
                } elseif ($diffInMints >= 10  && $banner->status == 'in-progress') {
                    DB::table('sponsorships')->where(['banner_position' => $banner->banner_position])->delete();
                }
            }
        }
    }
}
