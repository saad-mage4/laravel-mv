<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\WithdrawMethod;
use App\Models\SellerWithdraw;
use App\Models\OrderProduct;
use App\Models\Setting;
use App\Models\WithdrawSchedule;
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
        return view('seller.withdraw', compact('withdraws', 'setting'));
    }

    public function show($id)
    {
        $withdraw = SellerWithdraw::find($id);
        $setting = Setting::first();
        return view('seller.show_withdraw', compact('withdraw', 'setting'));
    }

    public function create()
    {
        //? only that code in the that function old code
        $methods = WithdrawMethod::whereStatus('1')->get();
        $setting = Setting::first();
        return view('seller.create_withdraw', compact('methods', 'setting'));
    }

    //! Automate Withdraw run after 14 days (Not Useable in the theme)
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


    public function getWithDrawAccountInfo($id)
    {
        $method = WithdrawMethod::whereId($id)->first();
        $setting = Setting::first();
        return view('seller.withdraw_account_info', compact('method', 'setting'));
    }

    // public function store(Request $request)
    // {
    //     $rules = [
    //         // 'method_id' => 'required',
    //         'withdraw_amount' => 'required|numeric',
    //         'account_info' => 'required',
    //     ];

    //     $customMessages = [
    //         // 'method_id.required' => trans('user_validation.Payment Method filed is required'),
    //         'withdraw_amount.required' => trans('user_validation.Withdraw amount filed is required'),
    //         'withdraw_amount.numeric' => trans('user_validation.Please provide valid numeric number'),
    //         'account_info.required' => trans('user_validation.Account filed is required'),
    //     ];

    //     $this->validate($request, $rules, $customMessages);

    //     $user = Auth::guard('web')->user();
    //     $seller = $user->seller;
    //     $totalAmount = 0;
    //     $orderProducts = OrderProduct::with('order')->where('seller_id', $seller->id)->get();
    //     foreach ($orderProducts as $orderProduct) {
    //         if ($orderProduct->order->payment_status == 1 && $orderProduct->order->order_status == 3) {
    //             $price = ($orderProduct->unit_price * $orderProduct->qty) + $orderProduct->vat;
    //             $totalAmount = $totalAmount + $price;
    //         }
    //     }

    //     $totalWithdraw = SellerWithdraw::where('seller_id', $seller->id)->where('status', 1)->sum('withdraw_amount');
    //     $currentAmount = $totalAmount - $totalWithdraw;
    //     if ($request->withdraw_amount > $currentAmount) {
    //         $notification = trans('Your Payment request is more than your current balance ' . $currentAmount);
    //         $notification = array('messege' => $notification, 'alert-type' => 'error');
    //         return redirect()->back()->with($notification);
    //     }

    //     $method = WithdrawMethod::whereId($request->method_id)->first();
    //     if ($request->withdraw_amount >= $method->min_amount && $request->withdraw_amount <= $method->max_amount) {
    //         $user = Auth::guard('web')->user();
    //         $seller = $user->seller;
    //         $widthdraw = new SellerWithdraw();
    //         $widthdraw->seller_id = $seller->id;
    //         $widthdraw->method = $method->name;
    //         $widthdraw->total_amount = $request->withdraw_amount;
    //         $withdraw_request = $request->withdraw_amount;
    //         $withdraw_amount = ($method->withdraw_charge / 100) * $withdraw_request;
    //         $widthdraw->withdraw_amount = $request->withdraw_amount - $withdraw_amount;
    //         $widthdraw->withdraw_charge = $method->withdraw_charge;
    //         $widthdraw->account_info = $request->account_info;
    //         $widthdraw->save();
    //         $notification = trans('user_validation.Withdraw request send successfully, please wait for admin approval');
    //         $notification = array('messege' => $notification, 'alert-type' => 'success');
    //         return redirect()->route('seller.my-withdraw.index')->with($notification);
    //     } else {
    //         $notification = trans('user_validation.Your amount range is not available');
    //         $notification = array('messege' => $notification, 'alert-type' => 'error');
    //         return redirect()->back()->with($notification);
    //     }
    // }

    public function store(Request $request)
    {
        $rules = [
            'withdraw_amount' => 'required|numeric',
            'account_info' => 'required',
        ];

        $customMessages = [
            'withdraw_amount.required' => trans('user_validation.Withdraw amount field is required'),
            'withdraw_amount.numeric' => trans('user_validation.Please provide a valid numeric number'),
            'account_info.required' => trans('user_validation.Account field is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        try {
            $user = Auth::guard('web')->user();
            $seller = $user->seller;
            $totalAmount = 0;
            $orderProducts = OrderProduct::with('order')->where('seller_id', $seller->id)->get();

            $price = [];
            foreach ($orderProducts as $orderProduct) {
                if ($orderProduct->order->payment_status == 1 && $orderProduct->order->order_status == 3) {
                    // $price = ($orderProduct->unit_price * $orderProduct->qty) + $orderProduct->vat;
                    // $totalAmount += $price;
                    $price[] = ($orderProduct->unit_price * $orderProduct->qty) + $orderProduct->vat;
                }
            }

            $totalPrice = array_sum($price);

            $totalWithdraw = SellerWithdraw::where('seller_id', $seller->id)
                ->where('status', 1)
                ->sum('withdraw_amount');

            $withdrawal = WithdrawSchedule::where('seller_id', $seller->id)->first();
            $availableAmount = $withdrawal->tax_amount;
            $currentAmount = $totalPrice - $availableAmount;
            // dd($currentAmount);
            // $totalAmount - $totalWithdraw;

            //! Check if the requested withdraw amount is more than the current balance
            if ($request->withdraw_amount > $currentAmount) {
                $notification = trans('Your Payment request is more than your current balance ' . $currentAmount);
                $notification = array('messege' => $notification, 'alert-type' => 'error');
                return redirect()->back()->with($notification);
            }

            // Process the withdraw request
            $withdraw = new SellerWithdraw();
            $withdraw->seller_id = $seller->id;
            $withdraw->total_amount = $request->withdraw_amount;
            $withdraw->withdraw_amount = $request->withdraw_amount;
            // $withdraw_amount = $totalPrice * 0.12;
            // $withdraw->withdraw_amount = $request->withdraw_amount - $withdraw_amount;
            $withdraw->withdraw_charge = $availableAmount;
            $withdraw->account_info = $request->account_info;
            $withdraw->status = 0; // Set to pending or awaiting admin approval
            $withdraw->save();

            $notification = trans('user_validation.Withdraw request sent successfully, please wait for admin approval');
            $notification = array('messege' => $notification, 'alert-type' => 'success');
            return redirect()->route('seller.my-withdraw.index')->with($notification);
        } catch (\Exception $e) {
            $notification = $e;
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->back()->with($notification);
        }
    }
}
