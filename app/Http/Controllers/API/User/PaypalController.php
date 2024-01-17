<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\PaymentExecution;

use App\Models\BreadcrumbImage;
use App\Models\Country;
use App\Models\CountryState;
use App\Models\City;
use App\Models\BillingAddress;
use App\Models\ShippingAddress;
use App\Models\Vendor;
use App\Models\ShippingMethod;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderProductVariant;
use App\Models\OrderAddress;
use App\Models\Product;
use App\Models\Setting;
use App\Models\StripePayment;
use App\Mail\OrderSuccessfully;
use App\Helpers\MailHelper;
use App\Models\EmailTemplate;
use App\Models\PaypalPayment;
use App\Models\Coupon;
use App\Models\ShoppingCart;
use Str;
use Cart;
use Mail;
use Session;
use Auth;

class PaypalController extends Controller
{
    private $apiContext;
    public function __construct()
    {
        $account = PaypalPayment::first();
        $paypal_conf = \Config::get('paypal');
        $this->apiContext = new ApiContext(new OAuthTokenCredential(
            $account->client_id,
            $account->secret_id,
            )
        );

        $setting=array(
            'mode' => $account->account_mode,
            'http.ConnectionTimeOut' => 30,
            'log.LogEnabled' => true,
            'log.FileName' => storage_path() . '/logs/paypal.log',
            'log.LogLevel' => 'ERROR'
        );
        $this->apiContext->setConfig($setting);
    }

    // public function paypalWebView(Request $request){
    //     $rules = [
    //         'agree_terms_condition'=>'required',
    //         'shipping_method'=>'required',
    //     ];
    //     $customMessages = [
    //         'agree_terms_condition.required' => trans('Agree field is required'),
    //     ];
    //     $this->validate($request, $rules,$customMessages);

    //     $agree_terms_condition = $request->agree_terms_condition;
    //     $shipping_method = $request->shipping_method;
    //     $token = $request->token;

    //     $user = Auth::guard('api')->user();
    //     Session::put('user', $user);
    //     Session::put('agree_terms_condition', $request->agree_terms_condition);
    //     Session::put('shipping_method', $request->shipping_method);

    //     return view('paypal_btn', compact('agree_terms_condition','shipping_method','token'));
    // }

    public function payWithPaypal(Request $request){
        $rules = [
            'agree_terms_condition'=>'required',
            'shipping_method'=>'required',
        ];
        $customMessages = [
            'agree_terms_condition.required' => trans('Agree field is required'),
        ];
        $this->validate($request, $rules,$customMessages);


        $tax_amount = 0;
        $total_price = 0;
        $coupon_price = 0;
        $shipping_fee = 0;

        $user = Auth::guard('api')->user();

        $billing = BillingAddress::where('user_id', $user->id)->first();
        $shipping = ShippingAddress::where('user_id', $user->id)->first();
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

        $subTotal = $subTotalAmount;
        $tax_amount = $taxAmount;
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

        $coupon_price = $couponAmount;
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
        $total_price = $totalAmount;


        $total_price = str_replace( array( '\'', '"', ',' , ';', '<', '>' ), '', $total_price);
        $setting = Setting::first();

        $additional_information = '';
        if($request->addition_information){
            $additional_information = $request->addition_information;
        }

        $agree_terms_condition = 'no';
        if($request->agree_terms_condition){
            $agree_terms_condition = $request->agree_terms_condition;
        }

        $amount_real_currency = $total_price;
        $amount_usd = round($total_price / $setting->currency_rate,2);
        $currency_rate = $setting->currency_rate;
        $currency_icon = $setting->currency_icon;
        $currency_name = $setting->currency_name;

        $amount_real_currency = $total_price;
        $paypalSetting = PaypalPayment::first();
        $payableAmount = round($total_price * $paypalSetting->currency_rate,2);

        $name=env('APP_NAME');

        // set payer
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        // set amount total
        $amount = new Amount();
        $amount->setCurrency($paypalSetting->currency_code)
            ->setTotal($payableAmount);

        // transaction
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setDescription(env('APP_NAME'));

        // redirect url
        $redirectUrls = new RedirectUrls();

        $root_url=url('/');
        $redirectUrls->setReturnUrl($root_url."/api/user/checkout/paypal-payment-success")
            ->setCancelUrl($root_url."/api/user/checkout/paypal-payment-cancled");

        // payment
        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));
        try {
            $payment->create($this->apiContext);
        } catch (\PayPal\Exception\PPConnectionException $ex) {

            $notification = trans('user_validation.Payment Faild');
            return response()->json(['Payment' => $Payment],403);
        }

        $approvalUrl = $payment->getApprovalLink();


        Session::put('user', $user);
        Session::put('agree_terms_condition', $request->agree_terms_condition);
        Session::put('shipping_method', $request->shipping_method);

        return redirect($approvalUrl);

        return response()->json(['approvalUrl' => $approvalUrl],200);

    }

    public function paypalPaymentSuccess(Request $request){

        if (empty($request->get('PayerID')) || empty($request->get('token'))) {

            $notification = trans('user_validation.Payment Faild');
            return response()->json(['notification' => $notification],403);
        }

        $payment_id=$request->get('paymentId');
        $payment = Payment::get($payment_id, $this->apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($request->get('PayerID'));
        /**Execute the payment **/
        $result = $payment->execute($execution, $this->apiContext);

        $user = Session::get('user');

        dd($user);

        if ($result->getState() == 'approved') {


            $tax_amount = 0;
            $total_price = 0;
            $coupon_price = 0;
            $shipping_fee = 0;

            $billing = BillingAddress::where('user_id', $user->id)->first();
            $shipping = ShippingAddress::where('user_id', $user->id)->first();

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

            $subTotal = $subTotalAmount;
            $tax_amount = $taxAmount;
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

            $coupon_price = $couponAmount;
            $totalAmount = $totalAmount - $couponAmount ;

            if($cartProducts->count() == 0){
                $notification = trans('user_validation.Your Shopping Cart is Empty');
                return response()->json(['message' => $notification],400);
            }


            $shipping_fee = 0;
            $shipping_method = Session::get('shipping_method');
            $shippingMethod = ShippingMethod::where('id',$shipping_method)->first();
            $shipping_fee = $shippingMethod->fee;
            $totalAmount = $totalAmount + $shipping_fee;
            $total_price = $totalAmount;

            $total_price = str_replace( array( '\'', '"', ',' , ';', '<', '>' ), '', $total_price);
            $setting = Setting::first();

            $additional_information = '';
            if(Session::get('addition_information')){
                $additional_information = Session::get('addition_information');
            }

            $agree_terms_condition = 'no';
            if(Session::get('agree_terms_condition')){
                $agree_terms_condition = Session::get('agree_terms_condition');
            }

            $amount_real_currency = $total_price;
            $amount_usd = round($total_price / $setting->currency_rate,2);
            $currency_rate = $setting->currency_rate;
            $currency_icon = $setting->currency_icon;
            $currency_name = $setting->currency_name;


            $order = new Order();
            $orderId = substr(rand(0,time()),0,10);
            $order->order_id = $orderId;
            $order->user_id = $user->id;
            $order->sub_total = $subTotal;
            $order->amount_real_currency = $total_price;
            $order->transection_id = $payment_id;
            $order->amount_usd = $amount_usd;
            $order->currency_rate = $currency_rate;
            $order->currency_icon = $currency_icon;
            $order->currency_name = $currency_name;
            $order->product_qty = $totalProduct;
            $order->payment_method = 'Paypal';
            $order->payment_status = 1;
            $order->shipping_method = $shippingMethod->title;
            $order->shipping_cost = $shippingMethod->fee;
            $order->coupon_coast = $coupon_price;
            $order->order_vat = $tax_amount;
            $order->order_status = 0;
            $order->additional_info = $additional_information;
            $order->agree_terms_condition = $agree_terms_condition;
            $order->save();

            if(Session::get('coupon_name')){
                $coupon = Coupon::where(['code' => Session::get('coupon_name')])->first();
                $qty = $coupon->apply_qty;
                $qty = $qty +1;
                $coupon->apply_qty = $qty;
                $coupon->save();
            }


            $findCoupon = ShoppingCart::where('user_id', $user->id)->first();

            if($findCoupon){
                if($findCoupon->coupon_name){
                    $coupon = Coupon::where(['code' => $findCoupon->coupon_name])->first();
                    $qty = $coupon->apply_qty;
                    $qty = $qty +1;
                    $coupon->apply_qty = $qty;
                    $coupon->save();
                }
            }

            $order_details = '';

            foreach($cartProducts as $key => $cartProduct){
                $productUnitPrice = 0;
                $productUnitPrice = $cartProduct->price;

                $product = Product::find($cartProduct->product_id);

                $orderProduct = new OrderProduct();
                $orderProduct->order_id = $order->id;
                $orderProduct->product_id = $cartProduct->product_id;
                $orderProduct->seller_id = $product->vendor_id;
                $orderProduct->product_name = $cartProduct->name;
                $orderProduct->unit_price = $productUnitPrice;
                $orderProduct->qty = $cartProduct->qty;
                $orderProduct->vat = $cartProduct->tax * $cartProduct->qty;
                $orderProduct->save();

                $productStock = Product::find($cartProduct->product_id);
                $qty = $productStock->qty - $cartProduct->qty;
                $productStock->qty = $qty;
                $productStock->save();

                foreach($cartProduct->variants as $index => $variant){
                    $productVariant = new OrderProductVariant();
                    $productVariant->order_product_id = $orderProduct->id;
                    $productVariant->product_id = $cartProduct->product_id;
                    $productVariant->variant_name = $variant->name;
                    $productVariant->variant_value = $variant->value;
                    $productVariant->variant_price = $variant->price;
                    $productVariant->save();
                }

                $order_details.='Product: '.$cartProduct->name. '<br>';
                $order_details.='Quantity: '. $cartProduct->qty .'<br>';
                $order_details.='Price: '.$setting->currency_icon . $cartProduct->qty * $productUnitPrice .'<br>';

            }

            $orderAddress = new OrderAddress();
            $orderAddress->order_id = $order->id;
            $orderAddress->billing_name = $billing->name;
            $orderAddress->billing_email = $billing->email;
            $orderAddress->billing_phone = $billing->phone;
            $orderAddress->billing_address = $billing->address;
            $orderAddress->billing_country = $billing->country ? $billing->country->name : '';
            $orderAddress->billing_state = $billing->countryState ? $billing->countryState->name : '';
            $orderAddress->billing_city = $billing->city ? $billing->city->name : '';
            $orderAddress->billing_zip_code = $billing->zip_code;
            $orderAddress->shipping_name = $shipping->name;
            $orderAddress->shipping_email = $shipping->email;
            $orderAddress->shipping_phone = $shipping->phone;
            $orderAddress->shipping_address = $shipping->address;
            $orderAddress->shipping_country = $shipping->country ? $shipping->country->name : '';
            $orderAddress->shipping_state = $shipping->countryState ? $shipping->countryState->name : '';
            $orderAddress->shipping_city = $shipping->city ? $shipping->city->name : '';
            $orderAddress->shipping_zip_code = $shipping->zip_code;
            $orderAddress->save();

            MailHelper::setMailConfig();

            $template=EmailTemplate::where('id',6)->first();
            $subject=$template->subject;
            $message=$template->description;
            $message = str_replace('{{user_name}}',$user->name,$message);
            $message = str_replace('{{total_amount}}',$setting->currency_icon.$total_price,$message);
            $message = str_replace('{{payment_method}}','Paypal',$message);
            $message = str_replace('{{payment_status}}','Success',$message);
            $message = str_replace('{{order_status}}','Pending',$message);
            $message = str_replace('{{order_date}}',$order->created_at->format('d F, Y'),$message);
            $message = str_replace('{{order_detail}}',$order_details,$message);
            Mail::to($user->email)->send(new OrderSuccessfully($message,$subject));

            $notification = trans('user_validation.Payment Successfully');

            return response()->json(['message' => $notification],200);
        }
    }

    public function paypalPaymentCancled(){
        $notification = trans('user_validation.Payment Faild');
        return response()->json(['notification' => $notification],403);
    }


}
