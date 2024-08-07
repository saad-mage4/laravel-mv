<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Product;
use App\Models\ProductReport;
use App\Models\ProductReview;
use App\Models\Vendor;
use App\Models\Subscriber;
use App\Models\User;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Brand;
use App\Models\OrderProduct;
use App\Models\SellerWithdraw;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\DB;

class SellerDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    //!! After Admin Approve the Seller Request

    public function index(){

        $user = Auth::guard('web')->user();
        $seller = $user->seller;
        if ($user->subscription_expiry_date != null && Carbon::now()->gt($user->subscription_expiry_date)) {
            // Subscription has expired, set is_member to 0
            $user->is_paid = 0;
            $user->save();
        }
        if($user->is_member == 0) {
            $notification = 'Your Monthly Subscription has been expired!';
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect('user/dashboard')->with($notification);
        }

        $todayOrders = Order::with('user')->whereHas('orderProducts',function($query) use ($user){
            $query->where('seller_id', $user->seller->id);
        })->orderBy('id','desc')->whereDay('created_at', now()->day)->get();



        $totalOrders = Order::with('user')->whereHas('orderProducts',function($query) use ($user){
            $query->where('seller_id', $user->seller->id);
        })->orderBy('id','desc')->get();

        $monthlyOrders = Order::with('user')->whereHas('orderProducts',function($query) use ($user){
            $query->where('seller_id', $user->seller->id);
        })->orderBy('id','desc')->whereMonth('created_at', now()->month)->get();


        $yearlyOrders = Order::with('user')->whereHas('orderProducts',function($query) use ($user){
            $query->where('seller_id', $user->seller->id);
        })->orderBy('id','desc')->whereYear('created_at', now()->year)->get();


        $setting = Setting::first();
        $products = Product::where('vendor_id', $seller->id)->get();

        $reviews = ProductReview::where('product_vendor_id', $seller->id)->get();
        $reports = ProductReport::where('seller_id', $seller->id)->get();

        $totalWithdraw = SellerWithdraw::where('seller_id',$seller->id)->where('status',1)->sum('withdraw_amount');
        $totalPendingWithdraw = SellerWithdraw::where('seller_id',$seller->id)->where('status',0)->sum('withdraw_amount');



        return view('seller.dashboard', compact('user', 'todayOrders', 'totalOrders', 'setting', 'monthlyOrders', 'yearlyOrders', 'products', 'reviews', 'reports', 'seller', 'totalWithdraw', 'totalPendingWithdraw'));
    }
}