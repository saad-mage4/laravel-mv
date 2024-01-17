<?php

namespace App\Http\Controllers\API\User;

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
use App\Models\PayStackAndMollie;
use App\Models\BankPayment;
use App\Models\InstamojoPayment;
use App\Models\PaypalPayment;
use App\Models\ShoppingCart;
use Cart;
use Session;
class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function checkout(){
        $user = Auth::guard('api')->user();
        $cartProducts = ShoppingCart::with('variants')->where('user_id', $user->id)->get();
        $cartProducts = ShoppingCart::with('variants')->where('user_id', $user->id)->get();
        $totalProduct = ShoppingCart::with('variants')->where('user_id', $user->id)->sum('qty');
        $subTotalAmount = 0;
        $taxAmount = 0;
        $couponAmount = 0;
        $totalAmount = 0;
        foreach($cartProducts as $cartProduct){
            $subTotalAmount += $cartProduct->price * $cartProduct->qty;
            $taxAmount += $cartProduct->tax * $cartProduct->qty;
        }

        $totalAmount = $taxAmount + $subTotalAmount;
        $findCoupon = ShoppingCart::where('user_id', $user->id)->first();

        if($findCoupon){
            if($findCoupon->offer_type == 1){
                $couponAmount = $findCoupon->coupon_price;
                $couponAmount = ($couponAmount / 100) * $totalAmount;
            }elseif($findCoupon->offer_type == 2){
                $couponAmount = $findCoupon->coupon_price;
            }
        }

        $totalAmount = $totalAmount - $couponAmount ;

        // if(!Session::get('is_billing')) {
        //     return redirect()->route('user.checkout.billing-address');
        // }
        if($cartProducts->count() == 0){
            $notification = trans('user_validation.Your Shopping Cart is Empty');
            return response()->json(['message' => $notification],400);
        }

        $shipping = ShippingAddress::where('user_id', $user->id)->first();
        $countries = Country::orderBy('name','asc')->where('status',1)->get();

        if($shipping){
            $shippingStates = CountryState::orderBy('name','asc')->where(['status' => 1, 'country_id' => $shipping->country_id])->get();
            $shippingCities = City::orderBy('name','asc')->where(['status' => 1, 'country_state_id' => $shipping->state_id])->get();
        }else{
            $shippingStates = CountryState::orderBy('name','asc')->where(['status' => 1, 'country_id' => 0])->get();
            $shippingCities = City::orderBy('name','asc')->where(['status' => 1, 'country_state_id' => 0])->get();
        }

        $billing = BillingAddress::where('user_id', $user->id)->first();

        if($billing){
            $billingStates = CountryState::orderBy('name','asc')->where(['status' => 1, 'country_id' => $billing->country_id])->get();
            $billingCities = City::orderBy('name','asc')->where(['status' => 1, 'country_state_id' => $billing->state_id])->get();
        }else{
            $billingStates = CountryState::orderBy('name','asc')->where(['status' => 1, 'country_id' => 0])->get();
            $billingCities = City::orderBy('name','asc')->where(['status' => 1, 'country_state_id' => 0])->get();
        }


        $banner = BreadcrumbImage::where(['id' => 2])->first();
        $shippingMethods = ShippingMethod::where('status',1)->get();
        $setting = Setting::first();
        $currencySetting = $setting;

        return response()->json(['banner' => $banner, 'cartProducts' =>$cartProducts, 'shipping' =>$shipping, 'shippingStates' =>$shippingStates, 'shippingCities' =>$shippingCities, 'countries' =>$countries, 'billing' => $billing, 'billingStates' => $billingStates, 'billingCities' => $billingCities  , 'shippingMethods' =>$shippingMethods, 'setting' =>$setting, 'totalProduct' => $totalProduct, 'subTotalAmount' => $subTotalAmount, 'taxAmount' => $taxAmount, 'couponAmount' => $couponAmount, 'totalAmount' => $totalAmount],200);


    }

    public function payment(Request $request){
        // if(!Session::get('is_billing') && !Session::put('is_shipping')) {
        //     return redirect()->route('user.checkout.billing-address');
        // }

        $rules = [
            'shipping_method'=>'required',
        ];
        $customMessages = [
            'shipping_method.required' => trans('Shipping method is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user = Auth::guard('api')->user();
        $cartProducts = ShoppingCart::with('variants')->where('user_id', $user->id)->get();
        $cartProducts = ShoppingCart::with('variants')->where('user_id', $user->id)->get();
        $totalProduct = ShoppingCart::with('variants')->where('user_id', $user->id)->sum('qty');
        $subTotalAmount = 0;
        $taxAmount = 0;
        $couponAmount = 0;
        $totalAmount = 0;
        foreach($cartProducts as $cartProduct){
            $subTotalAmount += $cartProduct->price * $cartProduct->qty;
            $taxAmount += $cartProduct->tax * $cartProduct->qty;
        }

        $totalAmount = $taxAmount + $subTotalAmount;
        $findCoupon = ShoppingCart::where('user_id', $user->id)->first();

        if($findCoupon){
            if($findCoupon->offer_type == 1){
                $couponAmount = $findCoupon->coupon_price;
                $couponAmount = ($couponAmount / 100) * $totalAmount;
            }elseif($findCoupon->offer_type == 2){
                $couponAmount = $findCoupon->coupon_price;
            }
        }

        $totalAmount = $totalAmount - $couponAmount ;

        if($cartProducts->count() == 0){
            $notification = trans('user_validation.Your Shopping Cart is Empty');
            return response()->json(['message' => $notification],400);
        }


        $shipping_fee = 0;
        $shipping_method = $request->shipping_method;
        $shippingMethod = ShippingMethod::where('id',$shipping_method)->first();
        $shipping_fee = $shippingMethod->fee;
        $totalAmount = $totalAmount + $shipping_fee;
        $setting = Setting::first();

        $stripe = StripePayment::first();
        $paypal = PaypalPayment::first();
        $payment_info = [
            'stripe' => $stripe,
            'paypal' => $paypal
        ];

        return response()->json(['cartProducts' => $cartProducts, 'totalProduct' => $totalProduct, 'subTotalAmount' => $subTotalAmount, 'taxAmount' => $taxAmount, 'couponAmount' => $couponAmount, 'shipping_fee' => $shipping_fee,'totalAmount' => $totalAmount, 'setting' => $setting, 'payment_info' => $payment_info],200);



        $banner = BreadcrumbImage::where(['id' => 2])->first();
        $cartContents = Cart::content();

        $stripe = StripePayment::first();
        $razorpay = RazorpayPayment::first();
        $flutterwave = Flutterwave::first();
        $user = Auth::guard('web')->user();
        $paystack = PayStackAndMollie::first();
        $bankPayment = BankPayment::first();
        $instamojoPayment = InstamojoPayment::first();
        $paypal = PaypalPayment::first();
        return view('payment', compact('banner','cartContents','shipping_fee','setting','stripe','razorpay','flutterwave','user','paystack','bankPayment','instamojoPayment','paypal'));
    }

    public function checkoutBillingAddress(){

        $user = Auth::guard('api')->user();
        $cartProducts = ShoppingCart::with('variants')->where('user_id', $user->id)->get();
        $cartProducts = ShoppingCart::with('variants')->where('user_id', $user->id)->get();
        $totalProduct = ShoppingCart::with('variants')->where('user_id', $user->id)->sum('qty');
        $subTotalAmount = 0;
        $taxAmount = 0;
        $couponAmount = 0;
        $totalAmount = 0;
        foreach($cartProducts as $cartProduct){
            $subTotalAmount += $cartProduct->price * $cartProduct->qty;
            $taxAmount += $cartProduct->tax * $cartProduct->qty;
        }

        $totalAmount = $taxAmount + $subTotalAmount;
        $findCoupon = ShoppingCart::where('user_id', $user->id)->first();

        if($findCoupon){
            if($findCoupon->offer_type == 1){
                $couponAmount = $findCoupon->coupon_price;
                $couponAmount = ($couponAmount / 100) * $totalAmount;
            }elseif($findCoupon->offer_type == 2){
                $couponAmount = $findCoupon->coupon_price;
            }
        }

        $totalAmount = $totalAmount - $couponAmount ;

        // if(!Session::get('is_billing')) {
        //     return redirect()->route('user.checkout.billing-address');
        // }
        if($cartProducts->count() == 0){
            $notification = trans('user_validation.Your Shopping Cart is Empty');
            return response()->json(['message' => $notification],400);
        }

        $billing = BillingAddress::where('user_id', $user->id)->first();
        $countries = Country::orderBy('name','asc')->where('status',1)->get();

        if($billing){
            $states = CountryState::orderBy('name','asc')->where(['status' => 1, 'country_id' => $billing->country_id])->get();
            $cities = City::orderBy('name','asc')->where(['status' => 1, 'country_state_id' => $billing->state_id])->get();
        }else{
            $states = CountryState::orderBy('name','asc')->where(['status' => 1, 'country_id' => 0])->get();
            $cities = City::orderBy('name','asc')->where(['status' => 1, 'country_state_id' => 0])->get();
        }
        $banner = BreadcrumbImage::where(['id' => 2])->first();
        $setting = Setting::first();

        return response()->json(['banner' => $banner, 'cartProducts' =>$cartProducts, 'billing' =>$billing, 'states' =>$states, 'cities' =>$cities, 'countries' =>$countries, 'setting' =>$setting, 'totalProduct' => $totalProduct, 'subTotalAmount' => $subTotalAmount, 'taxAmount' => $taxAmount, 'couponAmount' => $couponAmount, 'totalAmount' => $totalAmount],200);

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
        // Session::put('is_billing','yes');
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
            // Session::put('is_shipping','yes');
        }
        $notification = trans('user_validation.Update Successfully');
        return response()->json(['message' => $notification],200);
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
        // Session::put('is_shipping','yes');
        // Session::put('shipping_method',$request->shipping_method);
        // Session::put('shipping_method',$request->shipping_method);
        // if($request->agree_terms_condition){
        //     Session::put('agree_terms_condition','yes');
        // }
        // if($request->addition_information){
        //     Session::put('addition_information',$request->addition_information);
        // }
        $notification = trans('user_validation.Update Successfully');
        return response()->json(['message' => $notification],200);

        // return redirect()->route('user.checkout.payment');
    }





}
