<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Setting;
use App\Models\OrderProduct;
use App\Models\OrderProductVariant;
use App\Models\OrderAddress;
use Auth;
class SellerOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index(){
        $seller = Auth::guard('web')->user()->seller;
        $orders = Order::with('user')->whereHas('orderProducts',function($query) use ($seller){
            $query->where(['seller_id' => $seller->id]);
        })->orderBy('id','desc')->get();
        $title = trans('user_validation.All Orders');
        $setting = Setting::first();
        return view('seller.order', compact('orders','title','setting'));
    }

    public function pendingOrder(){
        $seller = Auth::guard('web')->user()->seller;
        $orders = Order::with('user')->whereHas('orderProducts',function($query) use ($seller){
            $query->where(['seller_id' => $seller->id]);
        })->orderBy('id','desc')->where('order_status',0)->get();
        $title = trans('user_validation.Pending Orders');
        $setting = Setting::first();
        return view('seller.order', compact('orders','title','setting'));
    }

    public function pregressOrder(){
        $seller = Auth::guard('web')->user()->seller;
        $orders = Order::with('user')->whereHas('orderProducts',function($query) use ($seller){
            $query->where(['seller_id' => $seller->id]);
        })->orderBy('id','desc')->where('order_status',1)->get();
        $title = trans('user_validation.Pregress Orders');
        $setting = Setting::first();
        return view('seller.order', compact('orders','title','setting'));
    }

    public function deliveredOrder(){
        $seller = Auth::guard('web')->user()->seller;
        $orders = Order::with('user')->whereHas('orderProducts',function($query) use ($seller){
            $query->where(['seller_id' => $seller->id]);
        })->orderBy('id','desc')->where('order_status',2)->get();
        $title = trans('user_validation.Delivered Orders');
        $setting = Setting::first();
        return view('seller.order', compact('orders','title','setting'));
    }

    public function completedOrder(){
        $seller = Auth::guard('web')->user()->seller;
        $orders = Order::with('user')->whereHas('orderProducts',function($query) use ($seller){
            $query->where(['seller_id' => $seller->id]);
        })->orderBy('id','desc')->where('order_status',3)->get();
        $title = trans('user_validation.Completed Orders');
        $setting = Setting::first();
        return view('seller.order', compact('orders','title','setting'));
    }

    public function declinedOrder(){
        $seller = Auth::guard('web')->user()->seller;
        $orders = Order::with('user')->whereHas('orderProducts',function($query) use ($seller){
            $query->where(['seller_id' => $seller->id]);
        })->orderBy('id','desc')->where('order_status',4)->get();
        $title = trans('user_validation.Declined Orders');
        $setting = Setting::first();
        return view('seller.order', compact('orders','title','setting'));
    }

    public function cashOnDelivery(){
        $seller = Auth::guard('web')->user()->seller;
        $orders = Order::with('user')->whereHas('orderProducts',function($query) use ($seller){
            $query->where(['seller_id' => $seller->id]);
        })->orderBy('id','desc')->where('cash_on_delivery',1)->get();

        $title = trans('user_validation.Cash On Delivery');
        $setting = Setting::first();
        return view('seller.order', compact('orders','title','setting'));
    }

    public function show($id){
        $order = Order::with('orderProducts','user')->find($id);
        $setting = Setting::first();
        return view('seller.show_order',compact('order','setting'));
    }
    public function updateOrderStatus(Request $request , $id){
        $rules = [
            'order_status' => 'required',
            'payment_status' => 'required',
        ];
        $this->validate($request, $rules);

        $order = Order::find($id);
        if($request->order_status == 0){
            $order->order_status = 0;
            $order->save();
        }else if($request->order_status == 1){
            $order->order_status = 1;
            $order->order_approval_date = date('Y-m-d');
            $order->save();
        }else if($request->order_status == 2){
            $order->order_status = 2;
            $order->order_delivered_date = date('Y-m-d');
            $order->save();
        }else if($request->order_status == 3){
            $order->order_status = 3;
            $order->order_completed_date = date('Y-m-d');
            $order->save();
        }else if($request->order_status == 4){
            $order->order_status = 4;
            $order->order_declined_date = date('Y-m-d');
            $order->save();
        }

        if($request->payment_status == 0){
            $order->payment_status = 0;
            $order->save();
        }elseif($request->payment_status == 1){
            $order->payment_status = 1;
            $order->payment_approval_date = date('Y-m-d');
            $order->save();
        }

        $notification = trans('admin_validation.Order Status Updated successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

}
