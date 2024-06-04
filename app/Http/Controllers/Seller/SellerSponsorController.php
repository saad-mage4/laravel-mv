<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Sponsorships;
use Illuminate\Contracts\{Foundation\Application, View\Factory, View\View};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};

class SellerSponsorController extends Controller
{
    public function index() {

    }

    /**
     * @return Application|Factory|View
     */
    public function showSponsor() {
        $user = Auth::guard('web')->user();
        $banners = DB::table('sponsorships')->get();
        return view('seller.show_sponsor', compact('banners'));
    }

    /**
     * @param Request $request
     * @return string
     */
    public function addSponsorReq(Request $request): string
    {
        if (DB::table('sponsorships')->where('banner_name', $request->banner_name)->exists()) {
            Sponsorships::class->updateSponsor($request);
            $response = 'Banner has been updated successfully!';
        } else {
            Sponsorships::class->updateSponsor($request);
            $response = 'Banner has been added successfully!';
        }
        return $response;
    }
}
