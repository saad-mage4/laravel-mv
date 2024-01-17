<?php

namespace App\Http\Controllers\Admin;

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
use Carbon\Carbon;
class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin-api');
    }

    public function dashobard(){
        $todayOrders = Order::with('user','orderProducts','orderAddress')->orderBy('id','desc')->whereDay('created_at', now()->day)->get();
        $totalOrders = Order::with('user','orderProducts','orderAddress')->orderBy('id','desc')->get();
        $monthlyOrders = Order::with('user','orderProducts','orderAddress')->orderBy('id','desc')->whereMonth('created_at', now()->month)->get();
        $yearlyOrders = Order::with('user','orderProducts','orderAddress')->orderBy('id','desc')->whereYear('created_at', now()->year)->get();
        $setting = Setting::first();
        $products = Product::with('category','seller','brand','gallery','specifications','reviews','variants','returnPolicy','tax','variantItems')->get();
        $reviews = ProductReview::with('user','product')->get();
        $reports = ProductReport::with('user','product','seller')->get();
        $users = User::with('city','seller','state', 'country')->get();
        $sellers = Vendor::with('user','socialLinks','products')->get();
        $subscribers = Subscriber::where('is_verified',1)->get();
        $blogs = Blog::with('category','comments')->get();
        $categories = Category::get();
        $brands = Brand::get();

        return response()->json(['todayOrders' => $todayOrders, 'totalOrders' => $totalOrders, 'setting' => $setting, 'monthlyOrders' => $monthlyOrders, 'yearlyOrders' => $yearlyOrders, 'products' => $products, 'reviews' => $reviews, 'reports' => $reports, 'users' => $users, 'sellers' => $sellers, 'subscribers' => $subscribers, 'blogs' => $blogs, 'categories' => $categories, 'brands' => $brands]);

        return view('admin.dashboard',compact('','','','','','','','','','','','','',''));
    }


}
