<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Country;
use App\Models\CountryState;
use App\Models\City;
use App\Models\BillingAddress;
use App\Models\ShippingAddress;
use App\Models\Vendor;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\OrderProduct;
use App\Models\Wishlist;
use App\Models\ProductReport;
use App\Models\GoogleRecaptcha;
use App\Models\BannerImage;
use App\Models\User;
use App\Rules\Captcha;
use Image;
use File;
use Str;
use Hash;
use Slug;

use App\Events\SellerToUser;
class UserProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function dashboard(){
        $user = Auth::guard('api')->user();
        $orders = Order::count();
        $wishlists = Wishlist::count();
        $reviews = ProductReview::where(['user_id' => $user->id, 'status' => 1])->count();

        return response()->json(['orders' => $orders, 'wishlists' => $wishlists, 'reviews' => $reviews]);
    }


    public function order(){
        $user = Auth::guard('api')->user();
        $orders = Order::with(['orderProducts.product'])->orderBy('id','desc')->where('user_id', $user->id)->paginate(10);
        return response()->json(['orders' => $orders]);
    }

    public function pendingOrder(){
        $user = Auth::guard('api')->user();
        $orders = Order::with(['orderProducts.product'])->orderBy('id','desc')->where('user_id', $user->id)->where('order_status',0)->paginate(10);
        return response()->json(['orders' => $orders]);
    }

    public function completeOrder(){
        $user = Auth::guard('api')->user();
        $orders = Order::with(['orderProducts.product'])->orderBy('id','desc')->where('user_id', $user->id)->where('order_status',3)->paginate(10);
        return response()->json(['orders' => $orders]);
    }

    public function declinedOrder(){
        $user = Auth::guard('api')->user();
        $orders = Order::with(['orderProducts.product'])->orderBy('id','desc')->where('user_id', $user->id)->where('order_status',4)->paginate(10);
        return response()->json(['orders' => $orders]);
    }

    public function orderShow($orderId){
        $user = Auth::guard('api')->user();
        $order = Order::with(['orderProducts.product','orderAddress'])->where('user_id', $user->id)->where('order_id',$orderId)->first();

        return response()->json(['order' => $order]);
    }


    public function wishlist(){
        $user = Auth::guard('api')->user();
        $wishlists = Wishlist::with('product')->where(['user_id' => $user->id])->paginate(10);

        return response()->json(['wishlists' => $wishlists]);
    }

    public function myProfile(){
        $user = Auth::guard('api')->user();
        $countries = Country::with('countryStates')->orderBy('name','asc')->where('status',1)->get();
        $states = CountryState::with('country','cities')->orderBy('name','asc')->where(['status' => 1, 'country_id' => $user->country_id])->get();
        $cities = City::with('countryState')->orderBy('name','asc')->where(['status' => 1, 'country_state_id' => $user->state_id])->get();
        $defaultProfile = BannerImage::whereId('15')->first();

        return response()->json(['user' => $user, 'countries' => $countries, 'states' => $states, 'cities' => $cities, 'defaultProfile' => $defaultProfile]);
    }

    public function updateProfile(Request $request){
        $user = Auth::guard('api')->user();
        $rules = [
            'name'=>'required',
            'email'=>'required|unique:users,email,'.$user->id,
            'phone'=>'required',
            'country'=>'required',
            'zip_code'=>'required',
            'address'=>'required',
        ];
        $customMessages = [
            'name.required' => trans('user_validation.Name is required'),
            'email.required' => trans('user_validation.Email is required'),
            'email.unique' => trans('user_validation.Email already exist'),
            'phone.required' => trans('user_validation.Phone is required'),
            'country.required' => trans('user_validation.Country is required'),
            'zip_code.required' => trans('user_validation.Zip code is required'),
            'address.required' => trans('user_validation.Address is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->country_id = $request->country;
        $user->state_id = $request->state;
        $user->city_id = $request->city;
        $user->zip_code = $request->zip_code;
        $user->address = $request->address;
        $user->save();

        if($request->file('image')){
            $old_image=$user->image;
            $user_image=$request->image;
            $extention=$user_image->getClientOriginalExtension();
            $image_name= Str::slug($request->name).date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name='uploads/custom-images/'.$image_name;

            Image::make($user_image)
                ->save(public_path().'/'.$image_name);

            $user->image=$image_name;
            $user->save();
            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }

        $notification = trans('user_validation.Update Successfully');
        return response()->json(['notification' => $notification]);
    }


    public function changePassword(){
        return view('user.change_password');
    }

    public function updatePassword(Request $request){
        $rules = [
            'current_password'=>'required',
            'password'=>'required|min:4|confirmed',
        ];
        $customMessages = [
            'current_password.required' => trans('user_validation.Current password is required'),
            'password.required' => trans('user_validation.Password is required'),
            'password.min' => trans('user_validation.Password minimum 4 character'),
            'password.confirmed' => trans('user_validation.Confirm password does not match'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user = Auth::guard('api')->user();
        if(Hash::check($request->current_password, $user->password)){
            $user->password = Hash::make($request->password);
            $user->save();
            $notification = 'Password change successfully';
            return response()->json(['notification' => $notification]);
        }else{
            $notification = trans('user_validation.Current password does not match');
            return response()->json(['notification' => $notification],403);
        }
    }

    public function address(){
        $user = Auth::guard('api')->user();
        $billing = BillingAddress::with('country','countryState','city')->where('user_id', $user->id)->first();
        $shipping = ShippingAddress::with('country','countryState','city')->where('user_id', $user->id)->first();

        return response()->json(['user' => $user, 'billing' => $billing, 'shipping' => $shipping]);
    }

    public function editBillingAddress(){
        $user = Auth::guard('api')->user();
        $billing = BillingAddress::with('country','countryState','city')->where('user_id', $user->id)->first();
        $countries = Country::with('countryStates')->orderBy('name','asc')->where('status',1)->get();

        if($billing){
            $states = CountryState::with('country','cities')->orderBy('name','asc')->where(['status' => 1, 'country_id' => $billing->country_id])->get();
            $cities = City::orderBy('name','asc')->where(['status' => 1, 'country_state_id' => $billing->state_id])->get();
        }else{
            $states = CountryState::orderBy('name','asc')->where(['status' => 1, 'country_id' => 0])->get();
            $cities = City::with('countryState')->orderBy('name','asc')->where(['status' => 1, 'country_state_id' => 0])->get();
        }

        return response()->json(['user' => $user, 'billing' => $billing, 'countries' => $countries, 'states' => $states, 'cities' => $cities]);
    }

    public function updateBillingAddress(Request $request){
        $rules = [
            'name'=>'required',
            'email'=>'required',
            'phone'=>'required',
            'country'=>'required',
            'address'=>'required',
        ];

        $customMessages = [
            'name.required' => trans('user_validation.Name is required'),
            'email.required' => trans('user_validation.Email is required'),
            'email.unique' => trans('user_validation.Email already exist'),
            'phone.required' => trans('user_validation.Phone is required'),
            'country.required' => trans('user_validation.Country is required'),
            'zip_code.required' => trans('user_validation.Zip code is required'),
            'address.required' => trans('user_validation.Address is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user = Auth::guard('api')->user();
        $billing = BillingAddress::where('user_id', $user->id)->first();
        if($billing){
            $billing->name = $request->name;
            $billing->email = $request->email;
            $billing->phone = $request->phone;
            $billing->country_id = $request->country;
            $billing->state_id = $request->state;
            $billing->city_id = $request->city;
            $billing->zip_code = $request->zip_code;
            $billing->address = $request->address;
            $billing->save();

            $notification = trans('user_validation.Update Successfully');
            return response()->json(['notification' => $notification]);
        }else{
            $billing = new BillingAddress();
            $billing->user_id = $user->id;
            $billing->name = $request->name;
            $billing->email = $request->email;
            $billing->phone = $request->phone;
            $billing->country_id = $request->country;
            $billing->state_id = $request->state;
            $billing->city_id = $request->city;
            $billing->zip_code = $request->zip_code;
            $billing->address = $request->address;
            $billing->save();

            $notification = trans('user_validation.Update Successfully');
            return response()->json(['notification' => $notification]);
        }
    }


    public function editShippingAddress(){
        $user = Auth::guard('api')->user();
        $shipping = ShippingAddress::with('country','countryState','city')->where('user_id', $user->id)->first();
        $countries = Country::with('countryStates')->orderBy('name','asc')->where('status',1)->get();

        if($shipping){
            $states = CountryState::with('country','cities')->orderBy('name','asc')->where(['status' => 1, 'country_id' => $shipping->country_id])->get();
            $cities = City::orderBy('name','asc')->where(['status' => 1, 'country_state_id' => $shipping->state_id])->get();
        }else{
            $states = CountryState::orderBy('name','asc')->where(['status' => 1, 'country_id' => 0])->get();
            $cities = City::with('countryState')->orderBy('name','asc')->where(['status' => 1, 'country_state_id' => 0])->get();
        }

        return response()->json(['user' => $user, 'shipping' => $shipping, 'countries' => $countries, 'states' => $states, 'cities' => $cities]);
    }

    public function updateShippingAddress(Request $request){
        $rules = [
            'name'=>'required',
            'email'=>'required',
            'phone'=>'required',
            'country'=>'required',
            'address'=>'required',
        ];

        $customMessages = [
            'name.required' => trans('user_validation.Name is required'),
            'email.required' => trans('user_validation.Email is required'),
            'email.unique' => trans('user_validation.Email already exist'),
            'phone.required' => trans('user_validation.Phone is required'),
            'country.required' => trans('user_validation.Country is required'),
            'zip_code.required' => trans('user_validation.Zip code is required'),
            'address.required' => trans('user_validation.Address is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user = Auth::guard('api')->user();
        $shipping = ShippingAddress::where('user_id', $user->id)->first();
        if($shipping){
            $shipping->name = $request->name;
            $shipping->email = $request->email;
            $shipping->phone = $request->phone;
            $shipping->country_id = $request->country;
            $shipping->state_id = $request->state;
            $shipping->city_id = $request->city;
            $shipping->zip_code = $request->zip_code;
            $shipping->address = $request->address;
            $shipping->save();

            $notification = trans('user_validation.Update Successfully');
            return response()->json(['notification' => $notification]);
        }else{
            $shipping = new ShippingAddress();
            $shipping->user_id = $user->id;
            $shipping->name = $request->name;
            $shipping->email = $request->email;
            $shipping->phone = $request->phone;
            $shipping->country_id = $request->country;
            $shipping->state_id = $request->state;
            $shipping->city_id = $request->city;
            $shipping->zip_code = $request->zip_code;
            $shipping->address = $request->address;
            $shipping->save();

            $notification = trans('user_validation.Update Successfully');
            return response()->json(['notification' => $notification]);
        }
    }


    public function stateByCountry($id){
        $states = CountryState::with('country','cities')->where(['status' => 1, 'country_id' => $id])->get();
        return response()->json(['states'=>$states]);
    }

    public function cityByState($id){
        $cities = City::with('countryState')->where(['status' => 1, 'country_state_id' => $id])->get();
        return response()->json(['cities'=>$cities]);
    }

    public function sellerRegistration(){
        $setting = Setting::first();
        return response()->json(['setting' => $setting]);
    }

    public function sellerRequest(Request $request){

        $user = Auth::guard('api')->user();
        $seller = Vendor::where('user_id',$user->id)->first();
        if($seller){
            $notification = 'Request Already exist';
            return response()->json(['notification' => $notification],400);
        }

        $rules = [
            'banner_image'=>'required',
            'shop_name'=>'required|unique:vendors',
            'email'=>'required|unique:vendors',
            'phone'=>'required',
            'address'=>'required',
            'open_at'=>'required',
            'closed_at'=>'required',
            'agree_terms_condition' => 'required'
        ];

        $customMessages = [
            'banner_image.required' => trans('Banner image is required'),
            'shop_name.required' => trans('user_validation.Shop name is required'),
            'shop_name.unique' => trans('user_validation.Shop name already exist'),
            'email.required' => trans('user_validation.Email is required'),
            'email.unique' => trans('user_validation.Email already exist'),
            'phone.required' => trans('user_validation.Phone is required'),
            'address.required' => trans('user_validation.Address is required'),
            'open_at.required' => trans('user_validation.Open at is required'),
            'closed_at.required' => trans('user_validation.Close at is required'),
            'agree_terms_condition.required' => trans('user_validation.Agree field is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user = Auth::guard('api')->user();
        $seller = new Vendor();
        $seller->shop_name = $request->shop_name;
        $seller->slug = Str::slug($request->shop_name);
        $seller->email = $request->email;
        $seller->phone = $request->phone;
        $seller->address = $request->address;
        $seller->greeting_msg = trans('user_validation.Welcome to'). ' '. $request->shop_name;
        $seller->open_at = $request->open_at;
        $seller->closed_at = $request->closed_at;
        $seller->user_id = $user->id;
        $seller->seo_title = $request->shop_name;
        $seller->seo_description = $request->shop_name;

        if($request->banner_image){
            $exist_banner = $seller->banner_image;
            $extention = $request->banner_image->getClientOriginalExtension();
            $banner_name = 'seller-banner'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $banner_name = 'uploads/custom-images/'.$banner_name;
            Image::make($request->banner_image)
                ->save(public_path().'/'.$banner_name);
            $seller->banner_image = $banner_name;
            $seller->save();
            if($exist_banner){
                if(File::exists(public_path().'/'.$exist_banner))unlink(public_path().'/'.$exist_banner);
            }
        }
        $seller->save();
        $notification = trans('user_validation.Request sumited successfully');
        return response()->json(['notification' => $notification],200);

    }



    public function addToWishlist($id){
        $user = Auth::guard('api')->user();
        $product = Product::find($id);
        $isExist = Wishlist::where(['user_id' => $user->id, 'product_id' => $product->id])->count();
        if($isExist == 0){
            $wishlist = new Wishlist();
            $wishlist->product_id = $id;
            $wishlist->user_id = $user->id;
            $wishlist->save();
            $message = trans('user_validation.Wishlist added successfully');
            return response()->json(['status' => 1, 'message' => $message]);
        }else{
            $message = trans('user_validation.Already added');
            return response()->json(['status' => 0, 'message' => $message]);
        }
    }

    public function removeWishlist($id){
        $wishlist = Wishlist::find($id);
        $wishlist->delete();
        $notification = trans('user_validation.Removed successfully');
        return response()->json(['notification' => $notification]);
    }

    public function storeProductReport(Request $request){
        if($request->subject == null){
            $message = trans('user_validation.Subject filed is required');
            return response()->json(['status' => 0, 'message' => $message]);
        }
        if($request->description == null){
            $message = trans('user_validation.Description filed is required');
            return response()->json(['status' => 0, 'message' => $message]);
        }
        if($request->product_id == null){
            $message = trans('user_validation.Product is required');
            return response()->json(['status' => 0, 'message' => $message]);
        }

        $user = Auth::guard('api')->user();
        $report = new ProductReport();
        $report->user_id = $user->id;
        $report->seller_id = $request->seller_id;
        $report->product_id = $request->product_id;
        $report->subject = $request->subject;
        $report->description = $request->description;
        $report->save();

        $message = trans('user_validation.Report Submited successfully');
        return response()->json(['status' => 1, 'message' => $message]);

    }

    public function review(){
        $user = Auth::guard('api')->user();
        $reviews = ProductReview::with('product','user')->orderBy('id','desc')->where(['user_id' => $user->id, 'status' => 1])->paginate(10);

        return response()->json(['user' => $user, 'reviews' => $reviews]);
    }

    public function showReview($id){
        $user = Auth::guard('api')->user();
        $review = ProductReview::with('product','user')->where(['user_id' => $user->id, 'status' => 1, 'id' => $id])->first();

        return response()->json(['user' => $user, 'review' => $review]);
    }




    public function storeProductReview(Request $request){
        $rules = [
            'rating'=>'required',
            'review'=>'required',
            'product_id'=>'required',
            'g-recaptcha-response'=>new Captcha()
        ];
        $customMessages = [
            'rating.required' => trans('user_validation.Rating is required'),
            'review.required' => trans('user_validation.Review is required'),
            'product_id.required' => trans('user_validation.Product is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user = Auth::guard('api')->user();
        $isExistOrder = false;
        $orders = Order::where(['user_id' => $user->id])->get();
        foreach ($orders as $key => $order) {
            foreach ($order->orderProducts as $key => $orderProduct) {
                if($orderProduct->product_id == $request->product_id){
                    $isExistOrder = true;
                }
            }
        }

        if($isExistOrder){
            $isReview = ProductReview::where(['product_id' => $request->product_id, 'user_id' => $user->id])->count();
            if($isReview > 0){
                $message = trans('user_validation.You have already submited review');
                return response()->json(['status' => 0, 'message' => $message]);
            }
            $review = new ProductReview();
            $review->user_id = $user->id;
            $review->rating = $request->rating;
            $review->review = $request->review;
            $review->product_vendor_id = $request->seller_id;
            $review->product_id = $request->product_id;
            $review->save();
            $message = trans('user_validation.Review Submited successfully');
            return response()->json(['status' => 1, 'message' => $message]);
        }else{
            $message = trans('user_validation.Opps! You can not review this product');
            return response()->json(['status' => 0, 'message' => $message]);
        }

    }

    public function updateReview(Request $request, $id){
        $rules = [
            'rating'=>'required',
            'review'=>'required',
        ];
        $this->validate($request, $rules);
        $user = Auth::guard('api')->user();
        $review = ProductReview::find($id);
        $review->rating = $request->rating;
        $review->review = $request->review;
        $review->save();

        $notification = trans('user_validation.Updated successfully');
        return response()->json(['notification' => $notification]);
    }




}
