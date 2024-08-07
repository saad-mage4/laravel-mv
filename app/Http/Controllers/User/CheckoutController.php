<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BreadcrumbImage;
use Auth;
use App\Models\Country;
use App\Models\CountryState;
use App\Models\City;
use App\Models\BillingAddress;
use App\Models\ShippingAddress;
use App\Models\Vendor;
use App\Models\ShippingMethod;
use App\Models\Setting;
use App\Models\Wishlist;
use App\Models\StripePayment;
use App\Models\RazorpayPayment;
use App\Models\Flutterwave;
use App\Models\PaystackAndMollie;
use App\Models\BankPayment;
use App\Models\InstamojoPayment;
use App\Models\PaypalPayment;
use App\Models\PaymongoPayment;
use App\Models\Product;
use App\Models\User;
use Cart;
use Illuminate\Support\Facades\DB;
use Session;
class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function checkout(){
        if(!Session::get('is_billing')) {
            return redirect()->route('user.checkout.billing-address');
        }
        if(Cart::count() == 0){
            $notification = trans('user_validation.Your Shopping Cart is Empty');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('product')->with($notification);
        }
        $cartContents = Cart::content();
        $user = Auth::guard('web')->user();
        $shipping = ShippingAddress::where('user_id', $user->id)->first();
        $countries = Country::orderBy('name','asc')->where('status',1)->get();

        if($shipping){
            $states = CountryState::orderBy('name','asc')->where(['status' => 1, 'country_id' => $countries[0]['id']])->get();
            $cities = City::orderBy('name','asc')->where(['status' => 1, 'country_state_id' => $shipping->state_id])->get();
        }else{
            $states = CountryState::orderBy('name','asc')->where(['status' => 1, 'country_id' => 0])->get();
            $cities = City::orderBy('name','asc')->where(['status' => 1, 'country_state_id' => 0])->get();
        }
        $banner = BreadcrumbImage::where(['id' => 2])->first();

        //!  setting shipping option by checking admin config
        $admin_check = ShippingMethod::where('super_admin_status', '1')->exists();

        if ($admin_check) {
            $shippingMethods = ShippingMethod::where([
                ['status', '=', 1],
                ['super_admin_status', '=', '1']
            ])->get();
        } else {
            // $userId = Auth::id();
            // foreach ($cartContents as $cart) {
            //     $products = Product::select('vendor_id')->where('id', $cart->id)->get();
            // }
            // // $products = Product::select('id')->where('vendor_id', $vendor->id)->first();

            // // $products = Product::where('vendor_id', $vendor->id)->get();
            // foreach ($products as $product) {
            //     $shippingMethods = ShippingMethod::where([
            //         ['status', '=', 1],
            //         ['vendor_id', '=', $product->vendor_id]
            //     ])->get();
            // }


            $vendorIds = [];
            foreach ($cartContents as $cart) {
                $product = Product::select('vendor_id')->where('id', $cart->id)->first();
                if ($product) {
                    $vendorIds[] = $product->vendor_id;
                }
            }

            // Remove duplicate vendor IDs
            $vendorIds = array_unique($vendorIds);

            // Initialize an array to store shipping methods
            $shippingMethods = [];

            // Fetch shipping methods for all unique vendor IDs
            if (!empty($vendorIds)) {
                $shippingMethods = ShippingMethod::whereIn('vendor_id', $vendorIds)
                    ->where('status', 1)
                    ->get();
            }



            //! Setting admin shipping []
            if ($shippingMethods->isEmpty()) {
                $shippingMethods = ShippingMethod::where([
                    ['status', '=', 1],
                    ['is_free', '=', '1']
                ])->get();
            }
        }

        // $shippingMethods = ShippingMethod::where('status',1)->get();
        $setting = Setting::first();
        $currencySetting = $setting;
        return view('checkout', compact('banner','cartContents','shipping','states','cities','countries','shippingMethods','setting','currencySetting'));
    }

    public function payment(){
        if(!Session::get('is_billing') && !Session::put('is_shipping')) {
            return redirect()->route('user.checkout.billing-address');
        }

        $shipping_fee = 0;
        $shipping_method = Session::get('shipping_method');
        $shippingMethod = ShippingMethod::where('id',$shipping_method)->first();
        $shipping_fee = $shippingMethod->fee;

        $banner = BreadcrumbImage::where(['id' => 2])->first();
        $cartContents = Cart::content();
        $setting = Setting::first();
        $stripe = StripePayment::first();
        $razorpay = RazorpayPayment::first();
        $flutterwave = Flutterwave::first();
        $user = Auth::guard('web')->user();
        $paystack = PaystackAndMollie::first();
        $bankPayment = BankPayment::first();
        $instamojoPayment = InstamojoPayment::first();
        $paypal = PaypalPayment::first();
        $paymongo = PaymongoPayment::first();
        return view('payment', compact('banner','cartContents','shipping_fee','setting','stripe','razorpay','flutterwave','user','paystack','bankPayment','instamojoPayment','paypal','paymongo'));
    }

    public function checkoutBillingAddress(){
        if(Cart::count() == 0){
            $notification = trans('user_validation.Your Shopping Cart is Empty');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('product')->with($notification);
        }
        $cartContents = Cart::content();
        $user = Auth::guard('web')->user();
        $billing = BillingAddress::where('user_id', $user->id)->first();
        $countries = Country::orderBy('name','asc')->where('status',1)->get();
        if($billing){
            $states = CountryState::orderBy('name','asc')->where(['status' => 1, 'country_id' => $countries[0]['id']])->get();
            $cities = City::orderBy('name','asc')->where(['status' => 1, 'country_state_id' => $billing->state_id])->get();
        }else{
            $states = CountryState::orderBy('name','asc')->where(['status' => 1, 'country_id' => 0])->get();
            $cities = City::orderBy('name','asc')->where(['status' => 1, 'country_state_id' => 0])->get();
        }
        $banner = BreadcrumbImage::where(['id' => 2])->first();
        $setting = Setting::first();
        return view('user.checkout_billing_address', compact('banner','billing','states','cities','countries','cartContents','setting'));
    }

    public function updateCheckoutBillingAddress(Request $request) {
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

        $user = Auth::guard('web')->user();
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
        }
        Session::put('is_billing','yes');
        if($request->same_as_shipping){
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
            }
            Session::put('is_shipping','yes');
        }
        $notification = trans('user_validation.Update Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('user.checkout.checkout');
    }

    public function updateShippingBillingAddress(Request $request){
        $rules = [
            'name'=>'required',
            'email'=>'required',
            'phone'=>'required',
            'country'=>'required',
            'address'=>'required',
            'shipping_method'=>'required',
            'agree_terms_condition'=>'required',
        ];

        $customMessages = [
            'name.required' => trans('user_validation.Name is required'),
            'email.required' => trans('user_validation.Email is required'),
            'email.unique' => trans('user_validation.Email already exist'),
            'phone.required' => trans('user_validation.Phone is required'),
            'country.required' => trans('user_validation.Country is required'),
            'zip_code.required' => trans('user_validation.Zip code is required'),
            'address.required' => trans('user_validation.Address is required'),
            'agree_terms_condition.required' => trans('user_validation.Agree field is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user = Auth::guard('web')->user();
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
        }
        Session::put('is_shipping','yes');
        Session::put('shipping_method',$request->shipping_method);
        Session::put('shipping_method',$request->shipping_method);
        if($request->agree_terms_condition){
            Session::put('agree_terms_condition','yes');
        }
        if($request->addition_information){
            Session::put('addition_information',$request->addition_information);
        }
        return redirect()->route('user.checkout.payment');
    }
}
