<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class SellerSponsorController extends Controller
{
    public function index() {
        
    }
    public function addSponsor(Request $request) {
        $user = Auth::guard('web')->user();
        return view('seller.add_sponsor');
    }
    public function addSponsorReq(Request $request) {
        dd($request->slot);
    }
}
