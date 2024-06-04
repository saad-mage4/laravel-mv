<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerSponsorController extends Controller
{
    public function index() {

    }
    public function showSponsor(Request $request) {
        $user = Auth::guard('web')->user();
        return view('seller.show_sponsor');
    }
    public function addSponsorReq(Request $request) {
        dd($request);
    }
}
