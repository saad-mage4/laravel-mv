<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BreadcrumbImage;
use App\Models\BannerImage;
use App\Models\ProductVariantItem;
use App\Models\Product;
use App\Models\CampaignProduct;
use App\Models\Coupon;
use App\Models\Setting;
use App\Models\ShoppingCart;
use App\Models\ShoppingCartVariant;
use Cart;
use Session;
use Auth;
class CartController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function cart(){
        $user = Auth::guard('api')->user();
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
        $setting = Setting::first();

        return response()->json(['cartProducts' => $cartProducts, 'totalProduct' => $totalProduct, 'subTotalAmount' => $subTotalAmount, 'taxAmount' => $taxAmount, 'couponAmount' => $couponAmount, 'totalAmount' => $totalAmount, 'setting' => $setting],200);
    }

    public function addToCart(Request $request){

        $user = Auth::guard('api')->user();

        $itemExist = false;
        $countProduct = ShoppingCart::where(['user_id' => $user->id, 'product_id' => $request->product_id])->count();
        if($countProduct > 0) $itemExist = true;
        if($itemExist) {
            $notification = trans('user_validation.Item already exist');
            return response()->json(['status' => 0, 'message' => $notification]);
        }

        $productStock = Product::find($request->product_id);
        if($productStock->qty == 0){
            $notification = trans('user_validation.Product stock out');
            return response()->json(['status' => 0, 'message' => $notification]);
        }

        if($productStock->qty < $request->quantity){
            $notification = trans('user_validation.Quantity not available in our stock');
            return response()->json(['status' => 0, 'message' => $notification]);
        }

        $variants = [];
        $values = [];
        $prices = [];
        $variantPrice = 0;
        if($request->variants){
            foreach($request->variants as $index => $varr){
                $variants[] = $request->variantNames[$index];
                $item = ProductVariantItem::where(['id' => $request->items[$index]])->first();
                $values[] = $item->name;
                $prices[] = $item->price;
            }
            $variantPrice = $variantPrice + array_sum($prices);
        }


        $product = Product::with('tax')->find($request->product_id);
        $tax_percentage = $product->tax->price;

        $isCampaign = false;
        $today = date('Y-m-d');
        $campaign = CampaignProduct::where(['status' => 1, 'product_id' => $product->id])->first();
        if($campaign){
            $campaign = $campaign->campaign;
            if($campaign->start_date <= $today &&  $today <= $campaign->end_date){
                $isCampaign = true;
            }
            $campaignOffer = $campaign->offer;
            $productPrice = $product->price;
            $campaignOfferPrice = ($campaignOffer / 100) * $productPrice;
            $totalPrice = $product->price;
            $campaignOfferPrice = $totalPrice - $campaignOfferPrice;
        }else{
            $totalPrice = $product->price;
            if($product->offer_price != null){
                $offerPrice = $product->offer_price;
            }
        }

        $productPrice = 0;
        if($isCampaign){
            $productPrice = $campaignOfferPrice + $variantPrice;
            $tax_percentage_amount = ($tax_percentage / 100) * $productPrice;
        }else{
            if ($product->offer_price == null) {
                $productPrice = $totalPrice + $variantPrice;
                $tax_percentage_amount = ($tax_percentage / 100) * $productPrice;
            }else {
                $productPrice = $product->offer_price + $variantPrice;
                $tax_percentage_amount = ($tax_percentage / 100) * $productPrice;
            }
        }

        $item = new ShoppingCart();
        $item->user_id = $user->id;
        $item->product_id = $product->id;
        $item->name = $product->short_name;
        $item->qty = $request->quantity;
        $item->price = $productPrice;
        $item->tax = $tax_percentage_amount;
        $item->tax = $tax_percentage_amount;
        $item->coupon_name = '';
        $item->offer_type = 0;
        $item->image = $request->image;
        $item->slug = $request->slug;
        $item->save();


        if($request->variants && $request->variantNames && $request->items){
            foreach($request->variants as $index => $varr){
                $variant = new ShoppingCartVariant();
                $variant->shopping_cart_id = $item->id;
                $variant->name = $request->variantNames[$index];
                $data = ProductVariantItem::where(['id' => $request->items[$index]])->first();
                $variant->value = $data->name;
                $variant->price = $data->price;
                $variant->save();
            }
        }

        $notification = trans('user_validation.Item added successfully');
        return response()->json(['status' => 1, 'message' => $notification]);
    }

    public function addToBuy(Request $request){
        $itemExist = false;
        $cartContents = Cart::content();
        foreach($cartContents as $cartContent){
            if($cartContent->id == $request->product_id) $itemExist = true;
        }

        if($itemExist) {
            $notification = trans('user_validation.Item Already Exist');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

        $variants = [];
        $values = [];
        $prices = [];
        $variantPrice = 0;
        if($request->variants){
            foreach($request->variants as $index => $varr){
                $variants[] = $request->variantNames[$index];
                $item = ProductVariantItem::where(['id' => $request->items[$index]])->first();
                $values[] = $item->name;
                $prices[] = $item->price;
            }
            $variantPrice = $variantPrice + array_sum($prices);
        }

        $product = Product::with('tax')->find($request->product_id);
        $tax_percentage = $product->tax->price;

        $isCampaign = false;
        $today = date('Y-m-d');
        $campaign = CampaignProduct::where(['status' => 1, 'product_id' => $product->id])->first();
        if($campaign){
            $campaign = $campaign->campaign;
            if($campaign->start_date <= $today &&  $today <= $campaign->end_date){
                $isCampaign = true;
            }
            $campaignOffer = $campaign->offer;
            $productPrice = $product->price;
            $campaignOfferPrice = ($campaignOffer / 100) * $productPrice;
            $totalPrice = $product->price;
            $campaignOfferPrice = $totalPrice - $campaignOfferPrice;
        }else{
            $totalPrice = $product->price;
            if($product->offer_price != null){
                $offerPrice = $product->offer_price;
            }
        }

        $productPrice = 0;
        if($isCampaign){
            $productPrice = $campaignOfferPrice + $variantPrice;
            $tax_percentage_amount = ($tax_percentage / 100) * $productPrice;
        }else{
            if ($product->offer_price == null) {
                $productPrice = $totalPrice + $variantPrice;
                $tax_percentage_amount = ($tax_percentage / 100) * $productPrice;
            }else {
                $productPrice = $product->offer_price + $variantPrice;
                $tax_percentage_amount = ($tax_percentage / 100) * $productPrice;
            }
        }

        $data=array();
        $data['id'] = $product->id;
        $data['name'] = $product->short_name;
        $data['qty'] = $request->quantity;
        $data['price'] = $productPrice;
        $data['weight'] = 1;
        $data['options']['tax'] = $tax_percentage_amount;
        $data['options']['coupon_price'] = 0;
        $data['options']['image'] = $request->image;
        $data['options']['slug'] = $request->slug;
        $data['options']['variants'] = $variants;
        $data['options']['values'] = $values;
        $data['options']['prices'] = $prices;
        Cart::add($data);

        $notification = trans('user_validation.Item added successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('cart')->with($notification);
    }



    public function cartItemRemove($rowId){

        $user = Auth::guard('api')->user();
        $cartProduct = ShoppingCart::where(['user_id' => $user->id, 'id' => $rowId])->first();
        ShoppingCartVariant::where('shopping_cart_id', $rowId)->delete();
        $cartProduct->delete();
        return $this->cart();
    }

    public function cartClear(){
        $user = Auth::guard('api')->user();
        $cartProducts = ShoppingCart::where(['user_id' => $user->id])->get();
        foreach($cartProducts as $cartProduct){
            ShoppingCartVariant::where('shopping_cart_id', $cartProduct->id)->delete();
            $cartProduct->delete();
        }

        return $this->cart();
    }


    public function applyCoupon(Request $request){
        if($request->coupon == null){
            $notification = trans('user_validation.Coupon Field is required');
            return response()->json(['status' => 0, 'message' => $notification]);
        }

        $user = Auth::guard('api')->user();
        $count = ShoppingCart::where('user_id', $user->id)->count();
        if($count == 0){
            $notification = trans('Your shopping cart is empty');
            return response()->json(['status' => 0, 'message' => $notification],400);
        }

        $coupon = Coupon::where(['code' => $request->coupon, 'status' => 1])->first();

        if($coupon->expired_date < date('Y-m-d')){
            $notification = trans('user_validation.Coupon already expired');
            return response()->json(['status' => 0, 'message' => $notification],403);
        }

        if($coupon->apply_qty >=  $coupon->max_quantity ){
            $notification = trans('user_validation.Sorry! You can not apply this coupon');
            return response()->json(['status' => 0, 'message' => $notification],403);
        }

        if($coupon){
            if($coupon->offer_type == 1){
                $coupon_price = $coupon->discount;
                $user = Auth::guard('api')->user();
                ShoppingCart::where('user_id', $user->id)->update(['coupon_price' => $coupon_price, 'offer_type' => 1, 'coupon_name' => $coupon->code]);
            }else {
                $coupon_price = $coupon->discount;
                $user = Auth::guard('api')->user();
                ShoppingCart::where('user_id', $user->id)->update(['coupon_price' => $coupon_price, 'offer_type' => 2, 'coupon_name' => $coupon->code]);
            }

            return $this->cart();

        }else{
            $notification = trans('user_validation.Invalid Coupon');
            return response()->json(['status' => 0, 'message' => $notification],403);
        }
    }

    public function cartUpdate($id){
        $stockOut = false;
        $user = Auth::guard('api')->user();
        $item = ShoppingCart::where(['user_id' => $user->id, 'id' => $id])->first();
        $product = Product::find($item->product_id);
        if($item->qty >= $product->qty){
            $stockOut = true;
        }

        if($stockOut){
            $notification = trans('user_validation.Quantity not available in our stock');
            return response()->json(['status' => 0, 'message' => $notification]);
        }

        $item->qty = $item->qty +1;
        $item->save();

        return $this->cart();

    }


    public function cartDecreement($id){
        $stockOut = false;
        $user = Auth::guard('api')->user();
        $item = ShoppingCart::where(['user_id' => $user->id, 'id' => $id])->first();
        $product = Product::find($item->product_id);
        if($item->qty >= $product->qty){
            $stockOut = true;
        }

        if($stockOut){
            $notification = trans('user_validation.Quantity not available in our stock');
            return response()->json(['status' => 0, 'message' => $notification]);
        }

        $item->qty = $item->qty - 1;
        $item->save();

        return $this->cart();

    }





    public function calculateProductPrice(Request $request) {
        $prices = [];
        $variantPrice = 0;
        if($request->variants){
            foreach($request->variants as $index => $varr){
                $item = ProductVariantItem::where(['id' => $request->items[$index]])->first();
                $prices[] = $item->price;
            }
            $variantPrice = $variantPrice + array_sum($prices);
        }

        $product = Product::with('tax')->find($request->product_id);


        $isCampaign = false;
        $today = date('Y-m-d');
        $campaign = CampaignProduct::where(['status' => 1, 'product_id' => $product->id])->first();
        if($campaign){
            $campaign = $campaign->campaign;
            if($campaign->start_date <= $today &&  $today <= $campaign->end_date){
                $isCampaign = true;
            }
            $campaignOffer = $campaign->offer;
            $productPrice = $product->price;
            $campaignOfferPrice = ($campaignOffer / 100) * $productPrice;
            $totalPrice = $product->price;
            $campaignOfferPrice = $totalPrice - $campaignOfferPrice;
        }else{
            $totalPrice = $product->price;
            if($product->offer_price != null){
                $offerPrice = $product->offer_price;
            }
        }

        $productPrice = 0;
        if($isCampaign){
            $productPrice = $campaignOfferPrice + $variantPrice;
        }else{
            if ($product->offer_price == null) {
                $productPrice = $totalPrice + $variantPrice;
            }else {
                $productPrice = $product->offer_price + $variantPrice;
            }
        }

        $productPrice = round($productPrice, 2);
        return response()->json(['productPrice' => $productPrice]);
    }

    public function sidebarcartItemRemove($rowId){
        Cart::remove($rowId);
        return response()->json(['status' => 1, 'message' => trans('user_validation.Item removed successfully')]);
    }

    public function loadSidebarCart(){
        return view('ajax_sidebar_cart');
    }

    public function calculateCartQty(){
        $qty = Cart::instance('default')->count();
        return response()->json(['qty' => $qty]);
    }

    public function loadMainCart(){
        $cartContents = Cart::content();
        $setting = Setting::first();
        $banners  = BannerImage::all();
        return view('ajax_cart_view', compact('cartContents', 'setting','banners'));
    }

    public function calculateWholsaleDiscount(Request $request){
        $qty = $request->qty;
        $product = Product::find($request->product_id);
        if($product->wholesales->count() > 0){
            $wholsale = '';
            if($product->wholesales->count() == 1){
                $wholsale = $product->wholesales->first();
            }else{
                $count = $product->wholesales->count();
                $lastChild = $product->wholesales[$count - 1];
                if($lastChild->minimum_product <= $qty){
                    $wholsale = $lastChild;
                }else{
                    $wholsales = $product->wholesales;
                    for($i = 0 ; $i < $count ; $i++){
                        if($qty >= $wholsales[$i]['minimum_product'] && $qty < $wholsales[$i + 1]['minimum_product']){
                            $wholsale = $wholsales[$i];
                        }
                    }
                }

            }
            if($wholsale){
                $price = $request->price;
                $offerPrice = ($wholsale->offer / 100) * $price;
                $price = $price - $offerPrice;
                $price = round($price,2);
                return response()->json(['status' => 1, 'price' => $price]);
            }else{
                return response()->json(['status' => 0]);
            }
        }else{
            return response()->json(['status' => 0]);
        }
    }
}
