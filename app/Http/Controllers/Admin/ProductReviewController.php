<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductReview;
use Auth;
class ProductReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }


    public function index(){
        $reviews = ProductReview::with('user', 'product')->orderBy('id', 'desc')->get();
        // $products = Product::orderBy('id', 'desc')->get();
        return view('admin.product_review', compact('reviews'));
    }

    public function show($id){
        $review = ProductReview::find($id);
        if($review){
            return view('admin.show_product_review',compact('review'));
        }else{
            $notification=trans('admin_validation.Something went wrong');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('admin.product-review')->with($notification);
        }

    }

    public function destroy($id)
    {
        $review = ProductReview::find($id);
        $review->delete();
        $notification = trans('admin_validation.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function changeStatus($id){
        $review = ProductReview::find($id);
        if($review->status == 1){
            $review->status = 0;
            $review->save();
            $message = trans('admin_validation.Inactive Successfully');
        }else{
            $review->status = 1;
            $review->save();
            $message = trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }
}