<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Setting;
use App\Models\OrderProduct;
use App\Models\OrderProductVariant;
use App\Models\OrderAddress;
class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin-api');
    }

    public function index(){
        $orders = Order::with('user','orderProducts','orderAddress')->orderBy('id','desc')->get();
        $title = trans('admin_validation.All Orders');
        $setting = Setting::first();

        return response()->json(['orders' => $orders, 'title' => $title, 'setting' => $setting], 200);
    }

    public function pendingOrder(){
        $orders = Order::with('user','orderProducts','orderAddress')->orderBy('id','desc')->where('order_status',0)->get();
        $title = trans('admin_validation.Pending Orders');
        $setting = Setting::first();

        return response()->json(['orders' => $orders, 'title' => $title, 'setting' => $setting], 200);
    }

    public function pregressOrder(){
        $orders = Order::with('user','orderProducts','orderAddress')->orderBy('id','desc')->where('order_status',1)->get();
        $title = trans('admin_validation.Pregress Orders');
        $setting = Setting::first();

        return response()->json(['orders' => $orders, 'title' => $title, 'setting' => $setting], 200);
    }

    public function deliveredOrder(){
        $orders = Order::with('user','orderProducts','orderAddress')->orderBy('id','desc')->where('order_status',2)->get();
        $title = trans('admin_validation.Delivered Orders');
        $setting = Setting::first();

        return response()->json(['orders' => $orders, 'title' => $title, 'setting' => $setting], 200);
    }

    public function completedOrder(){
        $orders = Order::with('user','orderProducts','orderAddress')->orderBy('id','desc')->where('order_status',3)->get();
        $title = trans('admin_validation.Completed Orders');
        $setting = Setting::first();
        return response()->json(['orders' => $orders, 'title' => $title, 'setting' => $setting], 200);
    }

    public function declinedOrder(){
        $orders = Order::with('user','orderProducts','orderAddress')->orderBy('id','desc')->where('order_status',4)->get();
        $title = trans('admin_validation.Declined Orders');
        $setting = Setting::first();
        return response()->json(['orders' => $orders, 'title' => $title, 'setting' => $setting], 200);
    }

    public function cashOnDelivery(){
        $orders = Order::with('user','orderProducts','orderAddress')->orderBy('id','desc')->where('cash_on_delivery',1)->get();
        $title = trans('admin_validation.Cash On Delivery');
        $setting = Setting::first();
        return response()->json(['orders' => $orders, 'title' => $title, 'setting' => $setting], 200);
    }

    public function show($id){
        $order = Order::with('user','orderProducts','orderAddress')->find($id);
        $setting = Setting::first();
        return response()->json(['order' => $order, 'setting' => $setting], 200);
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
        return response()->json(['notification' => $notification], 200);
    }


    public function destroy($id){
        $order = Order::find($id);
        $order->delete();
        $orderProducts = OrderProduct::where('order_id',$id)->get();
        $orderAddress = OrderAddress::where('order_id',$id)->first();
        foreach($orderProducts as $orderProduct){
            OrderProductVariant::where('order_product_id',$orderProduct->id)->delete();
            $orderProduct->delete();
        }
        OrderAddress::where('order_id',$id)->delete();

        $notification = trans('admin_validation.Delete successfully');
        return response()->json(['notification' => $notification], 200);
    }
}
