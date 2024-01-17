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
        $this->middleware('auth:api');
    }

    public function index(){
        $seller = Auth::guard('api')->user()->seller;
        $orders = Order::with('user','orderProducts','orderAddress')->whereHas('orderProducts',function($query) use ($seller){
            $query->where(['seller_id' => $seller->id]);
        })->orderBy('id','desc')->get();
        $title = trans('user_validation.All Orders');
        $setting = Setting::first();

        return response()->json(['orders' => $orders, 'title' => $title, 'setting' => $setting], 200);
    }

    public function pendingOrder(){
        $seller = Auth::guard('api')->user()->seller;
        $orders = Order::with('user','orderProducts','orderAddress')->whereHas('orderProducts',function($query) use ($seller){
            $query->where(['seller_id' => $seller->id]);
        })->orderBy('id','desc')->where('order_status',0)->get();
        $title = trans('user_validation.Pending Orders');
        $setting = Setting::first();
        return response()->json(['orders' => $orders, 'title' => $title, 'setting' => $setting], 200);
    }

    public function pregressOrder(){
        $seller = Auth::guard('api')->user()->seller;
        $orders = Order::with('user','orderProducts','orderAddress')->whereHas('orderProducts',function($query) use ($seller){
            $query->where(['seller_id' => $seller->id]);
        })->orderBy('id','desc')->where('order_status',1)->get();
        $title = trans('user_validation.Pregress Orders');
        $setting = Setting::first();
        return response()->json(['orders' => $orders, 'title' => $title, 'setting' => $setting], 200);
    }

    public function deliveredOrder(){
        $seller = Auth::guard('api')->user()->seller;
        $orders = Order::with('user','orderProducts','orderAddress')->whereHas('orderProducts',function($query) use ($seller){
            $query->where(['seller_id' => $seller->id]);
        })->orderBy('id','desc')->where('order_status',2)->get();
        $title = trans('user_validation.Delivered Orders');
        $setting = Setting::first();
        return response()->json(['orders' => $orders, 'title' => $title, 'setting' => $setting], 200);
    }

    public function completedOrder(){
        $seller = Auth::guard('api')->user()->seller;
        $orders = Order::with('user','orderProducts','orderAddress')->whereHas('orderProducts',function($query) use ($seller){
            $query->where(['seller_id' => $seller->id]);
        })->orderBy('id','desc')->where('order_status',3)->get();
        $title = trans('user_validation.Completed Orders');
        $setting = Setting::first();
        return response()->json(['orders' => $orders, 'title' => $title, 'setting' => $setting], 200);
    }

    public function declinedOrder(){
        $seller = Auth::guard('api')->user()->seller;
        $orders = Order::with('user','orderProducts','orderAddress')->whereHas('orderProducts',function($query) use ($seller){
            $query->where(['seller_id' => $seller->id]);
        })->orderBy('id','desc')->where('order_status',4)->get();
        $title = trans('user_validation.Declined Orders');
        $setting = Setting::first();
        return response()->json(['orders' => $orders, 'title' => $title, 'setting' => $setting], 200);
    }

    public function cashOnDelivery(){
        $seller = Auth::guard('api')->user()->seller;
        $orders = Order::with('user','orderProducts','orderAddress')->whereHas('orderProducts',function($query) use ($seller){
            $query->where(['seller_id' => $seller->id]);
        })->orderBy('id','desc')->where('cash_on_delivery',1)->get();

        $title = trans('user_validation.Cash On Delivery');
        $setting = Setting::first();
        return response()->json(['orders' => $orders, 'title' => $title, 'setting' => $setting], 200);
    }

    public function show($id){
        $order = Order::with('user','orderProducts','orderAddress')->find($id);
        $setting = Setting::first();

        return response()->json(['order' => $order, 'setting' => $setting], 200);

    }
}
