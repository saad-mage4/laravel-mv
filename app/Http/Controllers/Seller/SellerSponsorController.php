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
        $imagePath = null;
        // Check if an image is uploaded
        if ($request->hasFile('banner_img')) {
            $image = $request->file('banner_img');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('images/sponsors'), $imageName);
            $imagePath = 'images/sponsors/' . $imageName;
        }

        $sponsorship = new Sponsorships();

        if (DB::table('sponsorships')->where('banner_position', $request->image_position)->exists()) {
            $sponsorship->updateSponsor($request, $imagePath);
        } else {
            $sponsorship->addSponsor($request, $imagePath);
        }
        $notification = 'Banner has been updated successfully!';
            $notification = array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->back()->with($notification);
    }
}
