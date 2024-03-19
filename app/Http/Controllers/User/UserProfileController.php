<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Membership;
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
use App\Models\Message;
use App\Models\Category;
use App\Models\OrderProductVariant;
use App\Models\OrderAddress;

use Image;
use File;
use Str;
use Hash;
use Slug;

use App\Events\SellerToUser;
use Stripe\Customer;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class UserProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:web');
    }
    public function dashboard(){
        $user = Auth::guard('web')->user();
        $orders = Order::where('user_id',$user->id)->get();
        $wishlists = Wishlist::where('user_id',$user->id)->get();
        $reviews = ProductReview::where(['user_id' => $user->id, 'status' => 1])->get();
        return view('user.dashboard', compact('orders','reviews','wishlists'));
    }


    public function order(){
        $user = Auth::guard('web')->user();
        $orders = Order::orderBy('id','desc')->where('user_id', $user->id)->paginate(10);
        $setting = Setting::first();
        return view('user.order', compact('orders','setting'));
    }

    public function pendingOrder(){
        $user = Auth::guard('web')->user();
        $orders = Order::orderBy('id','desc')->where('user_id', $user->id)->where('order_status',0)->paginate(10);
        $setting = Setting::first();
        return view('user.order', compact('orders','setting'));
    }

    public function completeOrder(){
        $user = Auth::guard('web')->user();
        $orders = Order::orderBy('id','desc')->where('user_id', $user->id)->where('order_status',3)->paginate(10);
        $setting = Setting::first();
        return view('user.order', compact('orders','setting'));
    }

    public function declinedOrder(){
        $user = Auth::guard('web')->user();
        $orders = Order::orderBy('id','desc')->where('user_id', $user->id)->where('order_status',4)->paginate(10);
        $setting = Setting::first();
        return view('user.order', compact('orders','setting'));
    }

    public function orderShow($orderId){
        $user = Auth::guard('web')->user();
        $order = Order::where('user_id', $user->id)->where('order_id',$orderId)->first();
        $setting = Setting::first();
        $products = Product::all();
        return view('user.show_order', compact('order','setting','products'));
    }


    public function wishlist(){
        $user = Auth::guard('web')->user();
        $wishlists = Wishlist::where(['user_id' => $user->id])->paginate(10);
        $setting = Setting::first();
        return view('user.wishlist', compact('wishlists','setting'));
    }

    public function myProfile(){
        $user = Auth::guard('web')->user();
        $countries = Country::orderBy('name','asc')->where('status',1)->get();
        $states = CountryState::orderBy('name','asc')->where(['status' => 1, 'country_id' => $user->country_id])->get();
        $cities = City::orderBy('name','asc')->where(['status' => 1, 'country_state_id' => $user->state_id])->get();
        $defaultProfile = BannerImage::whereId('15')->first();
        return view('user.my_profile', compact('user','countries','cities','states','defaultProfile'));
    }

    public function updateProfile(Request $request){
        $user = Auth::guard('web')->user();
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
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
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

        $user = Auth::guard('web')->user();
        if(Hash::check($request->current_password, $user->password)){
            $user->password = Hash::make($request->password);
            $user->save();
            $notification = 'Password change successfully';
            $notification = array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->back()->with($notification);
        }else{
            $notification = trans('user_validation.Current password does not match');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
    }

    public function address(){
        $user = Auth::guard('web')->user();
        $billing = BillingAddress::where('user_id', $user->id)->first();
        $shipping = ShippingAddress::where('user_id', $user->id)->first();
        return view('user.address', compact('billing','shipping'));
    }

    public function editBillingAddress(){
        $user = Auth::guard('web')->user();
        $billing = BillingAddress::where('user_id', $user->id)->first();
        $countries = Country::orderBy('name','asc')->where('status',1)->get();

        if($billing){
            $states = CountryState::orderBy('name','asc')->where(['status' => 1, 'country_id' => $billing->country_id])->get();
            $cities = City::orderBy('name','asc')->where(['status' => 1, 'country_state_id' => $billing->state_id])->get();
        }else{
            $states = CountryState::orderBy('name','asc')->where(['status' => 1, 'country_id' => 0])->get();
            $cities = City::orderBy('name','asc')->where(['status' => 1, 'country_state_id' => 0])->get();
        }
        return view('user.edit_billing_address', compact('billing','countries','states','cities'));
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

            $notification = trans('user_validation.Update Successfully');
            $notification = array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->route('user.address')->with($notification);
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
            $notification = array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->route('user.address')->with($notification);
        }
    }


    public function editShippingAddress(){
        $user = Auth::guard('web')->user();
        $shipping = ShippingAddress::where('user_id', $user->id)->first();
        $countries = Country::orderBy('name','asc')->where('status',1)->get();

        if($shipping){
            $states = CountryState::orderBy('name','asc')->where(['status' => 1, 'country_id' => $shipping->country_id])->get();
            $cities = City::orderBy('name','asc')->where(['status' => 1, 'country_state_id' => $shipping->state_id])->get();
        }else{
            $states = CountryState::orderBy('name','asc')->where(['status' => 1, 'country_id' => 0])->get();
            $cities = City::orderBy('name','asc')->where(['status' => 1, 'country_state_id' => 0])->get();
        }
        return view('user.edit_shipping_address', compact('shipping','countries','states','cities'));
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

            $notification = trans('user_validation.Update Successfully');
            $notification = array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->route('user.address')->with($notification);
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
            $notification = array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->route('user.address')->with($notification);
        }
    }


    public function stateByCountry($id){
        $states = CountryState::where(['status' => 1, 'country_id' => $id])->get();
        $response='<option value="0">Select a State</option>';
        if($states->count() > 0){
            foreach($states as $state){
                $response .= "<option value=".$state->id.">".$state->name."</option>";
            }
        }
        return response()->json(['states'=>$response]);
    }

    public function cityByState($id){
        $cities = City::where(['status' => 1, 'country_state_id' => $id])->get();
        $response='<option value="0">Select Locality</option>';
        if($cities->count() > 0){
            foreach($cities as $city){
                $response .= "<option value=".$city->id.">".$city->name."</option>";
            }
        }
        return response()->json(['cities'=>$response]);
    }

    public function sellerMembership(){
        $setting = Setting::first();
        $user = Auth::guard('web')->user();
        $is_member = $user->is_member;
        $user->save();
        return view('user.seller_membership', compact('setting', 'is_member'));
    }

    public function subscribe(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $token = $request->stripeToken;

        $customer = Customer::create([
            'email' => $request->email,
            'source' => $token,
        ]);

        // Create a one-time payment intent for the customer
        $paymentIntent = PaymentIntent::create([
            'amount' => 1499, // Amount in cents ($15)
            'currency' => 'eur',
            'payment_method_types' => ['card'],
            'description' => 'One-time fee', // Optional description for the payment
            'confirm' => true,
            'customer' => $customer->id,
        ]);


        // Handle success or failure
        if ($paymentIntent->status === 'succeeded') {
            $setting = Setting::first();
            $user = Auth::guard('web')->user();
            $user->is_member = 1;
            $user->save();



            return redirect('user/membership/subscribe')->with('success', 'Subscription successful!');
        } else {
            return back()->withErrors(['stripe_error' => 'Payment failed.']);
        }
    }

    public function sellerRegistration(){
        $setting = Setting::first();
        $countries = Country::orderBy('name','asc')->where('status',1)->get();
        $states = CountryState::orderBy('name','asc')->where(['status' => 1, 'country_id' => $countries[0]['id']])->get();
        $cities = City::orderBy('name','asc')->where(['status' => 1])->get();
        $productCategories = Category::where(['status' => 1])->get();
        return view('user.seller_registration', compact('setting', 'states', 'cities','productCategories'));
    }

    public function sellerRequest(Request $request){

        $user = Auth::guard('web')->user();
        $categoryChecks = implode(',',$request->cat_check);
        $seller = Vendor::where('user_id',$user->id)->first();
        if($seller){
            $notification = 'Request Already exist';
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

        $rules = [
            'banner_image'=>'required',
            'shop_name'=>'required|unique:vendors',
            'email'=>'required|unique:vendors',
            'phone'=>'required',
            'address'=>'required',
            'open_at'=>'required',
            'closed_at'=>'required',
            'firstName'=>'required',
            'lastName'=>'required',
            'country'=>'required',
            'state'=>'required',
            'city'=>'required',
            'companyName'=>'required',
            'companyType'=>'required',
            'urc'=>'required',
            'iban'=>'required',
            'bank'=>'required',
            'swift'=>'required',
            'localCurrency'=>'required',
            'certificateRegistration'=>'required',
            'idCardSignatory' => 'required|mimes:png,jpeg,jpg',
            'bankStatement'=>'required',
            'articlesOfIncorporation'=>'required',
//            'firstName1'=>'required',
//            'lastName1'=>'required',
            'position'=>'required',
            'legalEmail'=>'required',
            'cLegalEmail'=>'required',
            'maxOrderTime'=>'required',
            'agree_terms_condition' => 'required',
            'certificateRegistration'=>'required|mimes:pdf,png,jpeg,jpg',
            'bankStatement'=>'required|mimes:pdf,png,jpeg,jpg',
            'articlesOfIncorporation'=>'required|mimes:pdf,png,jpeg,jpg'
//            'nic_front_image'=>'required',
//            'nic_back_image'=>'required',
//            'pdf'=>'required|mimes:pdf'
        ];

        $customMessages = [
            'banner_image.required' => trans('user_validation.Banner image is required'),
            'shop_name.required' => trans('user_validation.Shop name is required'),
            'shop_name.unique' => trans('user_validation.Shop name already exist'),
            'email.required' => trans('user_validation.Email is required'),
            'email.unique' => trans('user_validation.Email already exist'),
            'phone.required' => trans('user_validation.Phone is required'),
            'address.required' => trans('user_validation.Address is required'),
            'open_at.required' => trans('user_validation.Open at is required'),
            'closed_at.required' => trans('user_validation.Close at is required'),
            'firstName.required' => trans('user_validation.firstName'),
            'lastName.required' => trans('user_validation.lastName'),
            'country.required' => trans('user_validation.Company headquarters country location is required'),
            'state.required' => trans('user_validation.County is required'),
            'city.required' => trans('user_validation.Locality is required'),
            'companyName.required' => trans('user_validation.companyname'),
            'companyType.required' => trans('user_validation.companytype'),
            'urc.required' => trans('user_validation.urc'),
            'iban.required' => trans('user_validation.iban'),
            'bank.required' => trans('user_validation.bank'),
            'swift.required' => trans('user_validation.swift'),
            'localCurrency.required' => trans('user_validation.local'),
            'certificateRegistration.required' => trans('user_validation.certificate required'),
            'idCardSignatory.required' => trans('user_validation.ID Card required'),
            'idCardSignatory.mimes' => trans('user_validation.ID Card Types'),
            'bankStatement.required' => trans('user_validation.bank required'),
            'articlesOfIncorporation.required' => trans('user_validation.article required'),
//            'firstName1.required' => trans('user_validation.fname'),
//            'lastName1.required' => trans('user_validation.lname'),
            'position.required' => trans('user_validation.position'),
            'legalEmail.required' => trans('user_validation.confirm lemail'),
            'cLegalEmail.required' => trans('user_validation.confirm cemail'),
            'maxOrderTime.required' => trans('user_validation.max order'),
            'agree_terms_condition.required' => trans('user_validation.Agree field is required'),
            'certificateRegistration.mimes' => trans('user_validation.valid certificateRegistration'),
            'bankStatement.mimes' => trans('user_validation.valid bankStatement'),
            'articlesOfIncorporation.mimes' => trans('user_validation.valid articlesOfIncorporation'),
//            'nic_front_image.required' => trans('user_validation.NIC front required'),
//            'nic_back_image.required' => trans('user_validation.NIC back required'),
//            'pdf.required' => trans('user_validation.pdf required'),
            'pdf.mimes' => trans('user_validation.valid pdf'),
        ];
        $this->validate($request, $rules,$customMessages);
        // dd('asas',$request);
        $user = Auth::guard('web')->user();
        $seller = new Vendor();
        $seller->shop_name = $request->shop_name;
        $seller->slug = Str::slug($request->shop_name);
        $seller->email = $request->email;
        $seller->phone = $request->phone;
        $seller->address = $request->address;
        $seller->description = $request->about;
        $seller->greeting_msg = trans('user_validation.Welcome to'). ' '. $request->shop_name;
        $seller->open_at = $request->open_at;
        $seller->closed_at = $request->closed_at;
        $seller->firstName = $request->firstName;
        $seller->lastName = $request->lastName;
        $seller->postalCode = $request->postalCode;
        $seller->country = $request->country;
        $seller->state = $request->state;
        $seller->city = $request->city;
        $seller->companyName = $request->companyName;
        $seller->companyType = $request->companyType;
        $seller->urc = $request->urc;
        $seller->vat = $request->vat;
        $seller->iban = $request->iban;
        $seller->bank = $request->bank;
        $seller->swift = $request->swift;
        $seller->localCurrency = $request->localCurrency;
        $seller->producer = $request->producer;
        $seller->about = $request->about;
        $seller->firstName1 = $request->firstName1;
        $seller->lastName1 = $request->lastName1;
        $seller->position = $request->position;
        $seller->legalEmail = $request->legalEmail;
        $seller->cLegalEmail = $request->cLegalEmail;
        $seller->catcheck = $categoryChecks;
        $seller->period = $request->period;
        $seller->maxOrderTime = $request->maxOrderTime;
        $seller->user_id = $user->id;
        $seller->seo_title = $request->shop_name;
        $seller->seo_description = $request->shop_name;

        if($request->banner_image){
            $exist_front_nic = $seller->banner_image; // Correct variable name
            $extention = $request->banner_image->getClientOriginalExtension();
            $banner_name = 'seller-banner'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $banner_name = 'uploads/custom-images/'.$banner_name;
            Image::make($request->banner_image)
                ->save(public_path().'/'.$banner_name);
            $seller->banner_image = $banner_name;
            $seller->save();
            if($exist_front_nic){
                if(File::exists(public_path().'/'.$exist_front_nic)) // Correct variable name
                    unlink(public_path().'/'.$exist_front_nic); // Correct variable name
            }
        }

        if($request->nic_front_image){
            $exist_front_nic = $seller->nic_front_image;
            $extention_f = $request->nic_front_image->getClientOriginalExtension();
            $nic_front = 'nic-front'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention_f;
            $nic_front = 'uploads/custom-images/'.$nic_front;
            Image::make($request->nic_front_image)
                ->save(public_path().'/'.$nic_front);
            $seller->nic_front_image = $nic_front;
            $seller->save();
            if($exist_front_nic){
                if(File::exists(public_path().'/'.$exist_front_nic))unlink(public_path().'/'.$exist_front_nic);
            }
        }



        if($request->nic_back_image){
            $exist_back_nic = $seller->nic_back_image;
            $extention_b = $request->nic_back_image->getClientOriginalExtension();
            $nic_back = 'nic-back'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention_b;
            $nic_back = 'uploads/custom-images/'.$nic_back;
            Image::make($request->nic_back_image)
                ->save(public_path().'/'.$nic_back);
            $seller->nic_back_image = $nic_back;
            $seller->save();
            if($exist_back_nic){
                if(File::exists(public_path().'/'.$exist_back_nic))unlink(public_path().'/'.$exist_back_nic);
            }
        }

        if($request->idCardSignatory){
            $exist_id_Card = $seller->idCardSignatory;
            $extention_c = $request->idCardSignatory->getClientOriginalExtension();
            $id_card = 'id-card'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention_c;
            $id_card = 'uploads/custom-images/'.$id_card;
            Image::make($request->idCardSignatory)
                ->save(public_path().'/'.$id_card);
            $seller->idCardSignatory = $id_card;
            $seller->save();
            if($exist_id_Card){
                if(File::exists(public_path().'/'.$exist_id_Card))unlink(public_path().'/'.$exist_id_Card);
            }
        }

        if ($request->hasFile('pdf')) {
            // Check if the custom folder exists, create if not
            $customFolderPath = public_path('uploads/custom-pdf');
            if (!File::exists($customFolderPath)) {
                File::makeDirectory($customFolderPath, 0777, true, true);
            }

            $pdf = $seller->pdf;
            $extention_pdf = $request->file('pdf')->getClientOriginalExtension();
            $pdf_file = 'seller-pdf-' . date('Y-m-d-h-i-s') . '-' . rand(999, 9999) . '.' . $extention_pdf;
            $pdf_file_path = 'uploads/custom-pdf/' . $pdf_file;

            // Move the file to the custom folder
            $request->file('pdf')->move($customFolderPath, $pdf_file);

            // Update the seller's pdf field in the database
            $seller->pdf = $pdf_file_path;
            $seller->save();

            // Delete the old PDF if it exists
            if ($pdf && File::exists(public_path($pdf))) {
                unlink(public_path($pdf));
            }
        }

        if ($request->hasFile('certificateRegistration')) {
            // Check if the custom folder exists, create if not
            $customFolderPath = public_path('uploads/custom-pdf');
            if (!File::exists($customFolderPath)) {
                File::makeDirectory($customFolderPath, 0777, true, true);
            }

            $pdf1 = $seller->certificateRegistration;
            $extention_pdf = $request->file('certificateRegistration')->getClientOriginalExtension();
            $pdf_file = 'seller-pdf-' . date('Y-m-d-h-i-s') . '-' . rand(999, 9999) . '.' . $extention_pdf;
            $pdf_file_path = 'uploads/custom-pdf/' . $pdf_file;

            // Move the file to the custom folder
            $request->file('certificateRegistration')->move($customFolderPath, $pdf_file);

            // Update the seller's pdf field in the database
            $seller->certificateRegistration = $pdf_file_path;
            $seller->save();

            // Delete the old PDF if it exists
            if ($pdf1 && File::exists(public_path($pdf1))) {
                unlink(public_path($pdf1));
            }
        }

        if ($request->hasFile('bankStatement')) {
            // Check if the custom folder exists, create if not
            $customFolderPath = public_path('uploads/custom-pdf');
            if (!File::exists($customFolderPath)) {
                File::makeDirectory($customFolderPath, 0777, true, true);
            }

            $pdf2 = $seller->bankStatement;
            $extention_pdf = $request->file('bankStatement')->getClientOriginalExtension();
            $pdf_file = 'seller-pdf-' . date('Y-m-d-h-i-s') . '-' . rand(999, 9999) . '.' . $extention_pdf;
            $pdf_file_path = 'uploads/custom-pdf/' . $pdf_file;

            // Move the file to the custom folder
            $request->file('bankStatement')->move($customFolderPath, $pdf_file);

            // Update the seller's pdf field in the database
            $seller->bankStatement = $pdf_file_path;
            $seller->save();

            // Delete the old PDF if it exists
            if ($pdf2 && File::exists(public_path($pdf2))) {
                unlink(public_path($pdf2));
            }
        }

        if ($request->hasFile('articlesOfIncorporation')) {
            // Check if the custom folder exists, create if not
            $customFolderPath = public_path('uploads/custom-pdf');
            if (!File::exists($customFolderPath)) {
                File::makeDirectory($customFolderPath, 0777, true, true);
            }

            $pdf3 = $seller->articlesOfIncorporation;
            $extention_pdf = $request->file('articlesOfIncorporation')->getClientOriginalExtension();
            $pdf_file = 'seller-pdf-' . date('Y-m-d-h-i-s') . '-' . rand(999, 9999) . '.' . $extention_pdf;
            $pdf_file_path = 'uploads/custom-pdf/' . $pdf_file;

            // Move the file to the custom folder
            $request->file('articlesOfIncorporation')->move($customFolderPath, $pdf_file);

            // Update the seller's pdf field in the database
            $seller->articlesOfIncorporation = $pdf_file_path;
            $seller->save();

            // Delete the old PDF if it exists
            if ($pdf3 && File::exists(public_path($pdf3))) {
                unlink(public_path($pdf3));
            }
        }



        $seller->save();
        $notification = trans('user_validation.Request sumited successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('user.dashboard')->with($notification);

    }



    public function addToWishlist($id){
        $user = Auth::guard('web')->user();
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
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
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
        $user = Auth::guard('web')->user();
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
        $user = Auth::guard('web')->user();
        $reviews = ProductReview::orderBy('id','desc')->where(['user_id' => $user->id, 'status' => 1])->paginate(10);
        return view('user.review',compact('reviews'));
    }


    public function storeProductReview(Request $request){
        $rules = [
            'rating'=>'required',
            'review'=>'required',
            'g-recaptcha-response'=>new Captcha()
        ];
        $customMessages = [
            'rating.required' => trans('user_validation.Rating is required'),
            'review.required' => trans('user_validation.Review is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user = Auth::guard('web')->user();
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
        $user = Auth::guard('web')->user();
        $review = ProductReview::find($id);
        $review->rating = $request->rating;
        $review->review = $request->review;
        $review->save();

        $notification = trans('user_validation.Updated successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }


    public function delete_account(){

        if(env('APP_VERSION') == 0){
            $notification = trans('This Is Demo Version. You Can Not Change Anything');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

        $user = Auth::guard('web')->user();

        $isVendor = Vendor::where('user_id',$user->id)->first();
        if($isVendor) {
            $notification = trans('user_validation.You can not delete your seller account');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }


        $id = $user->id;
        $user_image = $user->image;

        if($user_image){
            if(File::exists(public_path().'/'.$user_image))unlink(public_path().'/'.$user_image);
        }
        ProductReport::where('user_id',$id)->delete();
        ProductReview::where('user_id',$id)->delete();
        ShippingAddress::where('user_id',$id)->delete();
        BillingAddress::where('user_id',$id)->delete();
        Wishlist::where('user_id',$id)->delete();
        Message::where('customer_id',$id)->delete();

        $orders = Order::where('user_id',$user->id)->get();

        foreach($orders as $order){
            $orderProducts = OrderProduct::where('order_id',$order->id)->get();
            $orderAddress = OrderAddress::where('order_id',$order->id)->first();
            foreach($orderProducts as $orderProduct){
                OrderProductVariant::where('order_product_id',$orderProduct->id)->delete();
                $orderProduct->delete();
            }
            OrderAddress::where('order_id',$order->id)->delete();
            $order->delete();
        }

        $user->delete();

        Auth::guard('web')->logout();

        $notification = trans('user_validation.Your account has been deleted successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('home')->with($notification);
    }


}
