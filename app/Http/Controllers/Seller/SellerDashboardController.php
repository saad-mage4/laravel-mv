<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Jobs\MarkAsAvailableJob;
use App\Jobs\MarkAsInReviewJob;
use App\Jobs\MarkAsPendingJob;
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
use App\Models\WithdrawSchedule;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\DB;

class SellerDashboardController extends Controller
{
    // protected $withdrawSchedules;
    public function __construct() //WithdrawSchedule $withdrawSchedules
    {
        $this->middleware('auth:web');
        // $this->withdrawSchedules = $withdrawSchedules;
    }

    //!! After Admin Approve the Seller Request



    public function index()
    {

        $user = Auth::guard('web')->user();
        $seller = $user->seller;
        if ($user->subscription_expiry_date != null && Carbon::now()->gt($user->subscription_expiry_date)) {
            // Subscription has expired, set is_member to 0
            $user->is_paid = 0;
            $user->save();
        }
        if ($user->is_member == 0) {
            $notification = 'Your Monthly Subscription has been expired!';
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect('user/dashboard')->with($notification);
        }

        $todayOrders = Order::with('user')->whereHas('orderProducts', function ($query) use ($user) {
            $query->where('seller_id', $user->seller->id);
        })->orderBy('id', 'desc')->whereDay('created_at', now()->day)->get();



        $totalOrders = Order::with('user')->whereHas('orderProducts', function ($query) use ($user) {
            $query->where('seller_id', $user->seller->id);
        })->orderBy('id', 'desc')->get();

        $monthlyOrders = Order::with('user')->whereHas('orderProducts', function ($query) use ($user) {
            $query->where('seller_id', $user->seller->id);
        })->orderBy('id', 'desc')->whereMonth('created_at', now()->month)->get();


        $yearlyOrders = Order::with('user')->whereHas('orderProducts', function ($query) use ($user) {
            $query->where('seller_id', $user->seller->id);
        })->orderBy('id', 'desc')->whereYear('created_at', now()->year)->get();


        $setting = Setting::first();
        $products = Product::where('vendor_id', $seller->id)->get();

        $reviews = ProductReview::where('product_vendor_id', $seller->id)->get();
        $reports = ProductReport::where('seller_id', $seller->id)->get();

        $totalWithdraw = SellerWithdraw::where('seller_id', $seller->id)->where('status', 1)->sum('withdraw_amount');
        // $totalPendingWithdraw = SellerWithdraw::where('seller_id', $seller->id)->where('status', 0)->sum('withdraw_amount');

        //! New Upwork Like WithDraw
        $check = WithdrawSchedule::where('seller_id', $seller->id)->exists();
        $withdrawal = new WithdrawSchedule();
        // $totalAmount = null;
        $totalPendingWithdraw = null;
        $inReviewWithdraw = null;
        $availableAmount = null;

        // date_default_timezone_set('Asia/Karachi');

        $orderProducts = OrderProduct::where('seller_id', $seller->id)->get();
        $price = [];
        foreach ($orderProducts as $orderProduct) {
            $price[] = ($orderProduct->unit_price * $orderProduct->qty) + $orderProduct->vat;
        }

        $totalPrice = array_sum($price);

        $totalAmount = $totalPrice;

        if (!$check) {
            $withdrawal->seller_id = $seller->id;
            $withdrawal->available_date = Carbon::now(); // Setting the initial available date
            $withdrawal->status = 'requested';
            $withdrawal->save();
        } else {
            $withdrawal = WithdrawSchedule::where('seller_id', $seller->id)->first();
            $current_date = $withdrawal->select('available_date')->where('seller_id', $seller->id)->first();

            //! for checking the logic validation testing
            // $currentTime = Carbon::now()->subDays(15)->format('Y-m-d');


            $currentTime = Carbon::now()->format('Y-m-d');

            $review_date = Carbon::parse($current_date->available_date)->diffInDays($currentTime);
            $pending_date = Carbon::parse($current_date->available_date)->diffInDays($currentTime);
            $available_date = Carbon::parse($current_date->available_date)->diffInDays($currentTime);



            if ($review_date >= 3 && $pending_date < 8) {
                $withdrawal->status = 'in_review';
                $inReviewWithdraw = $totalPrice;
            } elseif ($pending_date >= 8 && $available_date < 15) {
                $withdrawal->status = 'pending';
                $totalPendingWithdraw = $totalPrice;
            } elseif ($available_date >= 15) {
                $withdrawal->status = 'available';
                // $calculated_tax = $totalPrice * 0.12;
                $taxRate = 12; // Calculate 12% tax
                $calculated_tax = $totalPrice * ($taxRate / 100);
                $withdrawal->tax_rate = $taxRate;
                $withdrawal->tax_amount = $calculated_tax; // Set the tax amount
                $availableAmount = $totalPrice - $calculated_tax; // Calculate available amount
                // Set totalAmount to 0 if any withdraw values exist
            }
            if (!is_null($inReviewWithdraw) || !is_null($totalPendingWithdraw) || !is_null($availableAmount)) {
                $totalAmount = 0;
            }

            $withdrawal->update();
        }

        $widthdraw_Status = $withdrawal->status;
        $taxRate_Per = $withdrawal->tax_rate;



        return view('seller.dashboard', compact(
            'user',
            'todayOrders',
            'totalOrders',
            'setting',
            'monthlyOrders',
            'yearlyOrders',
            'products',
            'reviews',
            'reports',
            'seller',
            'totalWithdraw',
            'totalPendingWithdraw',
            'inReviewWithdraw',
            'availableAmount',
            'widthdraw_Status',
            'taxRate_Per',
            'totalAmount'
        ));
    }
}
