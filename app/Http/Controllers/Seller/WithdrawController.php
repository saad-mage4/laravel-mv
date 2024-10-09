<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\WithdrawMethod;
use App\Models\SellerWithdraw;
use App\Models\OrderProduct;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Auth;

class WithdrawController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index()
    {
        $user = Auth::guard('web')->user();
        $seller = $user->seller;
        $withdraws = SellerWithdraw::where('seller_id', $seller->id)->orderBy('created_at', 'desc')->get();
        $setting = Setting::first();

        //! Check if any withdraw is eligible for creation (i.e., has a payment status of 1)
        $daysLeftForNextWithdraw = null;
        if ($withdraws->isEmpty()) {
            $eligibleForWithdraw = true;
        } else {
            $eligibleForWithdraw = $withdraws->contains(function ($withdraw) use (&$daysLeftForNextWithdraw) {
                if (!empty($withdraw->approved_date)) {
                    $approvedDate = Carbon::parse($withdraw->approved_date);
                    $daysSinceApproval = $approvedDate->diffInDays(now());
                    $daysLeftForNextWithdraw = 14 - $daysSinceApproval;
                    return $withdraw->status == 1 && $daysSinceApproval >= 14;
                }
                return false; //if approvedDate is empty is return false
            });
        }
        return view('seller.withdraw', compact('withdraws', 'setting', 'eligibleForWithdraw', 'daysLeftForNextWithdraw'));
    }

    public function show($id)
    {
        $withdraw = SellerWithdraw::find($id);
        $setting = Setting::first();
        return view('seller.show_withdraw', compact('withdraw', 'setting'));
    }

    public function create()
    {

        $user = Auth::guard('web')->user();
        $seller = $user->seller;

        $withdraws = SellerWithdraw::where('seller_id', $seller->id)
            ->where('status', 1)
            ->get();

        $totalAmount = 0;
        $orderProducts = OrderProduct::with('order')->where('seller_id', $seller->id)->get();
        foreach ($orderProducts as $orderProduct) {
            if ($orderProduct->order->payment_status == 1 && $orderProduct->order->order_status == 3) {
                $price = ($orderProduct->unit_price * $orderProduct->qty) + $orderProduct->vat;
                $totalAmount = $totalAmount + $price;
            }
        }

        $totalWithdraw = SellerWithdraw::where('seller_id', $seller->id)->where('status', 1)->sum('withdraw_amount');
        $currentAmount = $totalAmount - $totalWithdraw;

        //! Check if any withdraw is eligible (14 days have passed)
        if ($withdraws->isEmpty()) {
            $eligibleForWithdraw = true;
        } else {
            $eligibleForWithdraw = $withdraws->contains(function ($withdraw) {
                if (!empty($withdraw->approved_date)) {
                    $approvedDate = Carbon::parse($withdraw->approved_date);
                    return $approvedDate->diffInDays(now()) >= 14;
                }
                return false;
            });
        }
        // Redirect if there are no eligible withdrawals
        if (!$eligibleForWithdraw) {
            $notification = array('messege' => 'You cannot create a withdraw request at this time.', 'alert-type' => 'error');
            return redirect()->back()->with($notification);
        }

        // only that code in the that function old code
        $methods = WithdrawMethod::whereStatus('1')->get();
        $setting = Setting::first();
        return view('seller.create_withdraw', compact('methods', 'setting', 'currentAmount'));
    }

    // Automate Withdraw run after 14 days
    public function automateWithdraw()
    {
        $orders = DB::table('orders')->whereNotNull('order_completed_date')->where('payment_status', '=', 0)->get();
        if (!empty($orders)) {
            foreach ($orders as $order) {
                $currentDate = $order->order_completed_date;
                $currentDate = Carbon::parse($currentDate);
                $currentCarbonDate = Carbon::parse($currentDate);
                $dateInFifteenDays = $currentCarbonDate->addDays(14);
                $diffInDays = $currentDate->diffInDays($dateInFifteenDays, false);

                date_default_timezone_set("Asia/Karachi");
                $current_time = date('Y-m-d');
                $order_completed_date = Carbon::createFromFormat('Y-m-d', $order->order_completed_date);
                $diff = $order_completed_date->diffInDays($current_time);
                if ($diff >= 14) {
                    DB::table('orders')->where('id', '=', $order->id)->update(['payment_status' => 1]);
                }
            }
        }
    }

    //! My Custom Work For automateWithdraw
    // public function automateWithdraw()
    // {
    //     $orders = DB::table('orders')
    //         ->whereNotNull('order_completed_date')
    //         ->where('payment_status', '=', 0) // assuming 0 means payment not available yet
    //         ->get();

    //     foreach ($orders as $order) {
    //         $orderCompletedDate = Carbon::parse($order->order_completed_date);
    //         $currentDate = Carbon::now();

    //         // Check if 14 days have passed
    //         if ($currentDate->diffInDays($orderCompletedDate) >= 14) {
    //             DB::table('orders')->where('id', $order->id)->update([
    //                 'payment_status' => 1, // Mark as available
    //             ]);
    //         }
    //     }
    // }


    public function getWithDrawAccountInfo($id)
    {
        $method = WithdrawMethod::whereId($id)->first();
        $setting = Setting::first();
        return view('seller.withdraw_account_info', compact('method', 'setting'));
    }

    public function store(Request $request)
    {
        $rules = [
            'method_id' => 'required',
            'withdraw_amount' => 'required|numeric',
            'account_info' => 'required',
        ];

        $customMessages = [
            'method_id.required' => trans('user_validation.Payment Method filed is required'),
            'withdraw_amount.required' => trans('user_validation.Withdraw amount filed is required'),
            'withdraw_amount.numeric' => trans('user_validation.Please provide valid numeric number'),
            'account_info.required' => trans('user_validation.Account filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $user = Auth::guard('web')->user();
        $seller = $user->seller;
        $totalAmount = 0;
        $orderProducts = OrderProduct::with('order')->where('seller_id', $seller->id)->get();
        foreach ($orderProducts as $orderProduct) {
            if ($orderProduct->order->payment_status == 1 && $orderProduct->order->order_status == 3) {
                $price = ($orderProduct->unit_price * $orderProduct->qty) + $orderProduct->vat;
                $totalAmount = $totalAmount + $price;
            }
        }

        $totalWithdraw = SellerWithdraw::where('seller_id', $seller->id)->where('status', 1)->sum('withdraw_amount');
        $currentAmount = $totalAmount - $totalWithdraw;
        if ($request->withdraw_amount > $currentAmount) {
            $notification = trans('Your Payment request is more than your current balance ' . $currentAmount);
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->back()->with($notification);
        }

        $method = WithdrawMethod::whereId($request->method_id)->first();
        if ($request->withdraw_amount >= $method->min_amount && $request->withdraw_amount <= $method->max_amount) {
            $user = Auth::guard('web')->user();
            $seller = $user->seller;
            $widthdraw = new SellerWithdraw();
            $widthdraw->seller_id = $seller->id;
            $widthdraw->method = $method->name;
            $widthdraw->total_amount = $request->withdraw_amount;
            $withdraw_request = $request->withdraw_amount;
            $withdraw_amount = ($method->withdraw_charge / 100) * $withdraw_request;
            $widthdraw->withdraw_amount = $request->withdraw_amount - $withdraw_amount;
            $widthdraw->withdraw_charge = $method->withdraw_charge;
            $widthdraw->account_info = $request->account_info;
            $widthdraw->save();
            $notification = trans('user_validation.Withdraw request send successfully, please wait for admin approval');
            $notification = array('messege' => $notification, 'alert-type' => 'success');
            return redirect()->route('seller.my-withdraw.index')->with($notification);
        } else {
            $notification = trans('user_validation.Your amount range is not available');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->back()->with($notification);
        }
    }


    //! My Work For Store the WithDraw
    // public function store(Request $request)
    // {
    //     $rules = [
    //         'method_id' => 'required',
    //         'withdraw_amount' => 'required|numeric',
    //         'account_info' => 'required',
    //     ];

    //     $customMessages = [
    //         'method_id.required' => trans('user_validation.Payment Method filed is required'),
    //         'withdraw_amount.required' => trans('user_validation.Withdraw amount filed is required'),
    //         'withdraw_amount.numeric' => trans('user_validation.Please provide valid numeric number'),
    //         'account_info.required' => trans('user_validation.Account filed is required'),
    //     ];

    //     $this->validate($request, $rules, $customMessages);

    //     // Get the authenticated seller
    //     $user = Auth::guard('web')->user();
    //     $seller = $user->seller;

    //     // Calculate Pending Amounts (still in 14-day hold)
    //     $pendingAmount = OrderProduct::with('order')
    //         ->where('seller_id', $seller->id)
    //         ->whereHas('order', function ($query) {
    //             $query->where('payment_status', 0); // payment not yet available
    //         })->sum(DB::raw('(unit_price * qty) + vat'));

    //     // Calculate Available Amount (after 14 days)
    //     $availableAmount = OrderProduct::with('order')
    //         ->where('seller_id', $seller->id)
    //         ->whereHas('order', function ($query) {
    //             $query->where('payment_status', 1); // payment available
    //         })->sum(DB::raw('(unit_price * qty) + vat'));

    //     // Calculate total withdrawals
    //     $totalWithdraw = SellerWithdraw::where('seller_id', $seller->id)->where('status', 1)->sum('withdraw_amount');
    //     $currentAmount = $availableAmount - $totalWithdraw;
    //     // dd($currentAmount);

    //     // Check if requested amount exceeds current balance
    //     if ($request->withdraw_amount > $currentAmount) {
    //         return redirect()->back()->with([
    //             'messege' => 'Sorry! Your Payment request is more than your current balance ' . $currentAmount,
    //             'alert-type' => 'warning'
    //         ]);
    //     }

    //     // Retrieve withdrawal method
    //     $method = WithdrawMethod::findOrFail($request->method_id);

    //     // Validate withdrawal amount against method limits
    //     if ($request->withdraw_amount >= $method->min_amount && $request->withdraw_amount <= $method->max_amount) {
    //         // Create a new withdrawal record
    //         $withdraw = new SellerWithdraw();
    //         $withdraw->seller_id = $seller->id;
    //         $withdraw->method = $method->name;
    //         $withdraw->total_amount = $request->withdraw_amount;

    //         // Calculate withdrawal charge and net amount
    //         $withdraw_request = $request->withdraw_amount;
    //         $withdraw_amount = ($method->withdraw_charge / 100) * $withdraw_request;
    //         $withdraw->withdraw_amount = $request->withdraw_amount - $withdraw_amount;
    //         $withdraw->withdraw_charge = $method->withdraw_charge;
    //         $withdraw->account_info = $request->account_info;

    //         // Save the withdrawal request
    //         $withdraw->save();

    //         return redirect()->route('seller.my-withdraw.index')->with([
    //             'messege' => trans('Withdraw request sent successfully, please wait for admin approval'),
    //             'alert-type' => 'success'
    //         ]);
    //     } else {
    //         return redirect()->back()->with([
    //             'messege' => trans('Your amount range is not available'),
    //             'alert-type' => 'error'
    //         ]);
    //     }
    // }
}
