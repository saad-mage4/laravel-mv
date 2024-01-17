<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\HomePageOneVisibility;
use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Slider;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ChildCategory;
use App\Models\CampaignProduct;
use App\Models\PopularCategory;
use App\Models\Product;
use App\Models\BannerImage;
use App\Models\ThreeColumnCategory;
use App\Models\Service;
use App\Models\Blog;
use App\Models\AboutUs;
use App\Models\ContactPage;
use App\Models\PopularPost;
use App\Models\BlogCategory;
use App\Models\BreadcrumbImage;
use App\Models\CustomPagination;
use App\Models\Faq;
use App\Models\CustomPage;
use App\Models\TermsAndCondition;
use App\Models\Vendor;
use App\Models\Subscriber;
use App\Mail\SubscriptionVerification;
use App\Mail\ContactMessageInformation;
use App\Helpers\MailHelper;
use App\Models\EmailTemplate;
use App\Models\ProductReview;
use App\Models\Setting;
use App\Models\ContactMessage;
use App\Models\BlogComment;
use App\Models\ProductVariant;
use App\Models\ProductVariantItem;
use App\Models\GoogleRecaptcha;
use App\Models\Order;
use App\Models\ShopPage;
use App\Models\SeoSetting;
use App\Rules\Captcha;
use Mail;
use Str;
use Session;
use Cart;
use Carbon\Carbon;
use Route;

use App\Models\AnnouncementModal;
use App\Models\MegaMenuCategory;
use App\Models\MenuVisibility;
use App\Models\GoogleAnalytic;
use App\Models\FacebookPixel;
use App\Models\TawkChat;
use App\Models\CookieConsent;


class HomeController extends Controller
{


    public function websiteSetup(){
        $setting = Setting::first();
        $announcementModal = AnnouncementModal::first();
        $productCategories = Category::where(['status' => 1])->get();
        $megaMenuCategories = MegaMenuCategory::orderBy('serial','asc')->where('status',1)->get();
        $megaMenuBanner = BannerImage::find(1);
        $modalProducts = Product::all();
        $currencySetting = Setting::first();
        $menus = MenuVisibility::select('status','id')->get();
        $customPages = CustomPage::where('status',1)->get();
        $googleAnalytic = GoogleAnalytic::first();
        $facebookPixel = FacebookPixel::first();
        $shop_page = ShopPage::first();
        $max_val = $shop_page->filter_price_range;
        $tawk_setting = TawkChat::first();
        $cookie_consent = CookieConsent::first();

        return response()->json(['setting' => $setting, 'announcementModal' => $announcementModal, 'productCategories' => $productCategories, 'megaMenuCategories' => $megaMenuCategories, 'megaMenuBanner' => $megaMenuBanner, 'modalProducts' => $modalProducts, 'currencySetting' => $currencySetting, 'menus' => $menus, 'customPages' => $customPages, 'googleAnalytic' => $googleAnalytic, 'facebookPixel' => $facebookPixel, 'shop_page' => $shop_page, 'max_val' => $max_val, 'tawk_setting' => $tawk_setting, 'cookie_consent' => $cookie_consent]);

    }


    public function index()
    {
        $brands = Brand::where(['status' => 1])->get();
        $visibilities = HomePageOneVisibility::all();
        $sliders = Slider::orderBy('serial','asc')->where(['status' => 1])->get();
        $productCategories = Category::with(['subCategories','products.activeReview'])->where(['status' => 1])->get();

        $campaign = '';
        $campaign = Campaign::with('products')->where(['show_homepage' => 1, 'status' => 1])->first();
        $campaignProducts = '';
        if($campaign){
            $campaignProducts = CampaignProduct::with('product.activeReview', 'campaign')->where(['campaign_id' => $campaign->id, 'show_homepage' => 1])->get();
            $end_time = strtotime($campaign->end_date);
            $start_time  = strtotime(date('Y-m-d'));
        }

        $popularCategory = PopularCategory::first();
        $firstCategory = Category::with('subCategories','products.activeReview')->find($popularCategory->category_id_one);
        $firstCategoryproducts = Product::with('category','seller','brand','gallery','specifications','activeReview','variants','returnPolicy','tax','variantItems')->where('category_id',$popularCategory->category_id_one);
        if($popularCategory->sub_category_id_one != 0){
            $firstCategoryproducts = $firstCategoryproducts->where('sub_category_id',$popularCategory->sub_category_id_one);
        }
        if($popularCategory->child_category_id_one != 0){
            $firstCategoryproducts = $firstCategoryproducts->where('child_category_id',$popularCategory->child_category_id_one);
        }
        $firstCategoryproducts = $firstCategoryproducts->get()->take($popularCategory->product_qty);

        $secondCategory = Category::with('subCategories','products.activeReview')->find($popularCategory->category_id_two);
        $secondCategoryproducts = Product::with('category','seller','brand','gallery','specifications','reviews','variants','returnPolicy','tax','variantItems')->where('category_id',$popularCategory->category_id_two);
        if($popularCategory->sub_category_id_two != 0){
            $secondCategoryproducts = $secondCategoryproducts->where('sub_category_id',$popularCategory->sub_category_id_two);
        }
        if($popularCategory->child_category_id_two != 0){
            $secondCategoryproducts = $secondCategoryproducts->where('child_category_id',$popularCategory->child_category_id_two);
        }
        $secondCategoryproducts = $secondCategoryproducts->get()->take($popularCategory->product_qty);

        $thirdCategory = Category::with('subCategories','products.activeReview')->find($popularCategory->category_id_three);
        $thirdCategoryproducts = Product::with('category','seller','brand','gallery','specifications','reviews','variants','returnPolicy','tax','variantItems')->where('category_id',$popularCategory->category_id_three);
        if($popularCategory->sub_category_id_three != 0){
            $thirdCategoryproducts = $thirdCategoryproducts->where('sub_category_id',$popularCategory->sub_category_id_three);
        }
        if($popularCategory->child_category_id_three !=0){
            $thirdCategoryproducts = $thirdCategoryproducts->where('child_category_id',$popularCategory->child_category_id_three);
        }
        $thirdCategoryproducts = $thirdCategoryproducts->get()->take($popularCategory->product_qty);

        $fourthCategory = Category::with('subCategories','products.activeReview')->find($popularCategory->category_id_four);
        $fourthCategoryproducts = Product::with('category','seller','brand','gallery','specifications','reviews','variants','returnPolicy','tax','variantItems')->where('category_id',$popularCategory->category_id_four);
        if($popularCategory->sub_category_id_four != 0){
            $fourthCategoryproducts = $fourthCategoryproducts->where('sub_category_id',$popularCategory->sub_category_id_four);
        }
        if($popularCategory->child_category_id_four != 0){
            $fourthCategoryproducts = $fourthCategoryproducts->where('child_category_id',$popularCategory->child_category_id_four);
        }
        $fourthCategoryproducts = $fourthCategoryproducts->get()->take($popularCategory->product_qty);

        $oneColumnBanner = BannerImage::whereId('2')->first();
        $banners  = BannerImage::all();
        $paginateQty = $visibilities->where('id',7)->first()->qty;
        $flashDealProducts = Product::with('category','seller','brand','gallery','specifications','activeReview','variants','returnPolicy','tax','variantItems')->where(['status' => 1, 'is_flash_deal' => 1])->get();
        $featuredProducts = Product::with('category','seller','brand','gallery','specifications','activeReview','variants','returnPolicy','tax','variantItems')->where(['status' => 1, 'is_featured' => 1])->get()->take($paginateQty);
        $bestProducts = Product::with('category','seller','brand','gallery','specifications','activeReview','variants','returnPolicy','tax','variantItems')->where(['status' => 1, 'is_best' => 1])->get()->take($paginateQty);
        $topProducts = Product::with('category','seller','brand','gallery','specifications','activeReview','variants','returnPolicy','tax','variantItems')->where(['status' => 1, 'is_top' => 1])->get()->take($paginateQty);
        $newProducts = Product::with('category','seller','brand','gallery','specifications','activeReview','variants','returnPolicy','tax','variantItems')->where(['status' => 1, 'new_product' => 1])->get()->take($paginateQty);

        $columnCategories = Category::with('subCategories','products.activeReview')->get();
        $threeColumnCategory = ThreeColumnCategory::first();
        $threeColumnFirstCategoryProducts = Product::where('category_id', $threeColumnCategory->category_id_one);
        if($threeColumnCategory->sub_category_id_one != 0){
            $threeColumnFirstCategoryProducts = $threeColumnFirstCategoryProducts->where('sub_category_id', $threeColumnCategory->sub_category_id_one);
        }
        if($threeColumnCategory->child_category_id_one != 0){
            $threeColumnFirstCategoryProducts = $threeColumnFirstCategoryProducts->where('child_category_id', $threeColumnCategory->child_category_id_one);
        }
        $threeColumnFirstCategoryProducts = $threeColumnFirstCategoryProducts->get();

        $threeColumnSecondCategoryProducts = Product::where('category_id', $threeColumnCategory->category_id_two);
        if($threeColumnCategory->sub_category_id_two != 0){
            $threeColumnSecondCategoryProducts = $threeColumnSecondCategoryProducts->where('sub_category_id', $threeColumnCategory->sub_category_id_two);
        }
        if($threeColumnCategory->child_category_id_two != 0){
            $threeColumnSecondCategoryProducts = $threeColumnSecondCategoryProducts->where('child_category_id', $threeColumnCategory->child_category_id_two);
        }
        $threeColumnSecondCategoryProducts = $threeColumnSecondCategoryProducts->get();

        $threeColumnThirdCategoryProducts = Product::where('category_id', $threeColumnCategory->category_id_three);
        if($threeColumnCategory->sub_category_id_three != 0){
            $threeColumnThirdCategoryProducts = $threeColumnThirdCategoryProducts->where('sub_category_id', $threeColumnCategory->sub_category_id_three);
        }
        if($threeColumnCategory->child_category_id_three != 0){
            $threeColumnThirdCategoryProducts = $threeColumnThirdCategoryProducts->where('child_category_id', $threeColumnCategory->child_category_id_three);
        }
        $threeColumnThirdCategoryProducts = $threeColumnThirdCategoryProducts->get();

        $services = Service::where('status',1)->get();
        $blogs = Blog::with('admin','category','comments')->where(['show_homepage' => 1, 'status' => 1])->get();
        $seoSetting = SeoSetting::find(1);
        $currencySetting = Setting::first();
        $setting = $currencySetting;

        return response()->json(['brands' => $brands, 'visibilities' => $visibilities, 'campaign' => $campaign, 'sliders' => $sliders, 'productCategories' => $productCategories, 'campaignProducts' => $campaignProducts, 'popularCategory' => $popularCategory, 'firstCategory' => $firstCategory, 'firstCategoryproducts' => $firstCategoryproducts, 'oneColumnBanner' => $oneColumnBanner, 'banners' => $banners, 'flashDealProducts' => $flashDealProducts, 'featuredProducts' => $featuredProducts, 'bestProducts' => $bestProducts, 'topProducts' => $topProducts, 'newProducts' => $newProducts, 'services' => $services, 'blogs' => $blogs, 'threeColumnFirstCategoryProducts' => $threeColumnFirstCategoryProducts, 'threeColumnCategory' => $threeColumnCategory, 'columnCategories' => $columnCategories, 'threeColumnSecondCategoryProducts' => $threeColumnSecondCategoryProducts, 'threeColumnSecondCategoryProducts' => $threeColumnSecondCategoryProducts, 'threeColumnThirdCategoryProducts' => $threeColumnThirdCategoryProducts, 'seoSetting' => $seoSetting, 'secondCategory' => $secondCategory, 'secondCategoryproducts' => $secondCategoryproducts, 'thirdCategory' => $thirdCategory, 'thirdCategoryproducts' => $thirdCategoryproducts, 'fourthCategory' => $fourthCategory, 'fourthCategoryproducts' => $fourthCategoryproducts, 'currencySetting' => $currencySetting, 'setting' => $setting], 200);
    }

    public function productRating(){
        $ratings = ProductReview::where('status', 1)->get();
        return response()->json(['ratings' => $ratings],200);
    }

    public function aboutUs(){
        $aboutUs = AboutUs::first();
        $seoSetting = SeoSetting::find(2);

        return response()->json(['aboutUs' => $aboutUs, 'seoSetting' => $seoSetting]);
    }

    public function contactUs(){
        $contact = ContactPage::first();
        $recaptchaSetting = GoogleRecaptcha::first();
        $seoSetting = SeoSetting::find(3);

        return response()->json(['contact' => $contact, 'recaptchaSetting' => $recaptchaSetting, 'seoSetting' => $seoSetting]);
    }

    public function sendContactMessage(Request $request){
        $rules = [
            'name'=>'required',
            'email'=>'required',
            'subject'=>'required',
            'message'=>'required',
            'g-recaptcha-response'=>new Captcha()
        ];
        $this->validate($request, $rules);

        $setting = Setting::first();
        if($setting->enable_save_contact_message == 1){
            $contact = new ContactMessage();
            $contact->name = $request->name;
            $contact->email = $request->email;
            $contact->subject = $request->subject;
            $contact->phone = $request->phone;
            $contact->message = $request->message;
            $contact->save();
        }

        MailHelper::setMailConfig();
        $template = EmailTemplate::where('id',2)->first();
        $message = $template->description;
        $subject = $template->subject;
        $message = str_replace('{{name}}',$request->name,$message);
        $message = str_replace('{{email}}',$request->email,$message);
        $message = str_replace('{{phone}}',$request->phone,$message);
        $message = str_replace('{{subject}}',$request->subject,$message);
        $message = str_replace('{{message}}',$request->message,$message);

        Mail::to($setting->contact_email)->send(new ContactMessageInformation($message,$subject));

        $notification = trans('user_validation.Message send successfully');
        return response()->json(['notification' => $notification]);
    }

    public function blog(){
        $paginateQty = CustomPagination::whereId('1')->first()->qty;
        $blogs = Blog::with('comments','admin','category')->orderBy('id','desc')->where(['status' => 1])->paginate($paginateQty);
        $banner = BreadcrumbImage::where(['id' => 10])->first();
        $seoSetting = SeoSetting::find(6);

        return response()->json(['blogs' => $blogs, 'banner' => $banner, 'seoSetting' => $seoSetting]);
    }

    public function blogDetail($slug){
        $blog = Blog::with('comments','admin','category')->where(['status' => 1, 'slug'=>$slug])->first();
        $blog->views += 1;
        $blog->save();
        $relatedBlogs = Blog::with('comments','admin','category')->where(['status' => 1, 'blog_category_id' => $blog->blog_category_id])->where('id', '!=', $blog->id)->get();
        $popularPosts = PopularPost::with('blog')->where(['status' => 1])->get();
        $categories = BlogCategory::where(['status' => 1])->get();
        $recaptchaSetting = GoogleRecaptcha::first();

        return response()->json(['blog' => $blog, 'relatedBlogs' => $relatedBlogs, 'popularPosts' => $popularPosts, 'categories' => $categories, 'recaptchaSetting' => $recaptchaSetting]);
    }

    public function blogByCategory($slug){
        $paginateQty = CustomPagination::whereId('1')->first()->qty;
        $category = BlogCategory::where('slug',$slug)->first();
        $blogs = Blog::with('comments','admin','category')->orderBy('id','desc')->where(['status' => 1, 'blog_category_id' => $category->id])->paginate($paginateQty);
        $banner = BreadcrumbImage::where(['id' => 10])->first();
        $seoSetting = SeoSetting::find(6);

        return response()->json(['blogs' => $blogs, 'banner' => $banner, 'seoSetting' => $seoSetting]);
    }

    public function blogSearch(Request $request){
        $paginateQty = CustomPagination::whereId('1')->first()->qty;
        $blogs = Blog::orderBy('id','desc')
                    ->where(['status' => 1])
                    ->where('title','LIKE','%'.$request->search.'%')
                    ->orWhere('description','LIKE','%'.$request->search.'%')
                    ->paginate($paginateQty);
        $banner = BreadcrumbImage::where(['id' => 10])->first();
        $seoSetting = SeoSetting::find(6);

        return response()->json(['blogs' => $blogs, 'banner' => $banner, 'seoSetting' => $seoSetting]);
    }

    public function blogComment(Request $request){
        $rules = [
            'name'=>'required',
            'email'=>'required',
            'comment'=>'required',
            'blog_id'=>'required',
            'g-recaptcha-response'=>new Captcha()
        ];
        $this->validate($request, $rules);

        $comment = new BlogComment();
        $comment->blog_id = $request->blog_id;
        $comment->name = $request->name;
        $comment->email = $request->email;
        $comment->comment = $request->comment;
        $comment->save();

        $notification = trans('user_validation.Blog comment submited successfully');

        return response()->json(['status' => 1, 'message' => $notification]);
    }

    public function campaign(){
        $banner = BreadcrumbImage::where(['id' => 3])->first();
        $campaigns = Campaign::with('products')->orderBy('id','desc')->where('status',1)->get();
        $seoSetting = SeoSetting::find(7);

        return response()->json(['banner' => $banner, 'campaigns' => $campaigns, 'seoSetting' => $seoSetting]);
    }

    public function campaignDetail($slug){
        $banner = BreadcrumbImage::where(['id' => 3])->first();
        $campaign = Campaign::where(['status' => 1, 'slug' => $slug])->first();
        $campaignProducts = CampaignProduct::with('product.activeReview', 'campaign')->where(['campaign_id' => $campaign->id, 'show_homepage' => 1])->get();
        $currencySetting = Setting::first();

        $bannerOne = BannerImage::whereId('11')->first();
        $bannerTwo = BannerImage::whereId('12')->first();
        $setting = Setting::first();

        $category = Category::where('status',1)->get();
        $brands = Brand::where('status',1)->get();

        return response()->json(['banner' => $banner, 'campaign' => $campaign, 'campaignProducts' => $campaignProducts, 'currencySetting' => $currencySetting, 'bannerOne' => $bannerOne, 'bannerTwo' => $bannerTwo, 'setting' => $setting, 'category' => $category, 'brands' => $brands]);
    }

    public function brand(){
        $paginateQty = CustomPagination::whereId('3')->first()->qty;
        $brands = Brand::orderBy('id','desc')->where('status',1)->paginate($paginateQty);
        $banner = BreadcrumbImage::where(['id' => 1])->first();
        $seoSetting = SeoSetting::find(4);

        return response()->json(['brands' => $brands, 'banner' => $banner, 'seoSetting' => $seoSetting]);

    }

    public function trackOrder(){
        $banner = BreadcrumbImage::where(['id' => 7])->first();
        return view('track_order', compact('banner'));
    }

    public function trackOrderResponse($id){
        if(!$id){
            $message = trans('user_validation.Order id is required');
            return response()->json(['status'=> 0, 'message' => $message]);
        }
        $order = Order::where('order_id',$id)->first();
        if($order){

            return response()->json(['order'=> $order]);
        }else{
            $message = trans('user_validation.Order not found');
            return response()->json(['status'=> 0, 'message' => $message]);
        }
    }

    public function faq(){
        $banner = BreadcrumbImage::where(['id' => 4])->first();
        $faqs = FAQ::orderBy('id','desc')->where('status',1)->get();

        return response()->json(['banner'=> $banner, 'faqs' => $faqs]);
    }


    public function allCustomPage(){
        $pages = CustomPage::where(['status' => 1])->get();
        return response()->json(['pages'=> $pages]);
    }

    public function customPage($slug){
        $page = CustomPage::where(['slug' => $slug, 'status' => 1])->first();
        return response()->json(['page'=> $page]);
    }

    public function termsAndCondition(){
        $terms_conditions = TermsAndCondition::first();
        return response()->json(['terms_conditions'=> $terms_conditions]);
    }

    public function privacyPolicy(){
        $privacyPolicy = TermsAndCondition::first();
        return response()->json(['privacyPolicy'=> $privacyPolicy]);
    }

    public function seller(){
        $banner = BreadcrumbImage::where(['id' => 8])->first();
        $sellers = Vendor::with('socialLinks', 'user', 'products')->orderBy('id','desc')->where('status',1)->get();
        $productReviews = ProductReview::with('product','user')->get();
        $seoSetting = SeoSetting::find(5);

        return response()->json(['banner'=> $banner, 'sellers' => $sellers, 'productReviews' => $productReviews, 'seoSetting' => $seoSetting]);

    }

    public function sellerDetail(Request $request){
        $slug = $request->shop_name;
        $banner = BreadcrumbImage::where(['id' => 8])->first();
        $seller = Vendor::with('socialLinks', 'user', 'products')->where(['status' => 1, 'slug' => $slug])->first();
        $paginateQty = CustomPagination::whereId('2')->first()->qty;
        $products = Product::with('category','seller','brand','gallery','specifications','activeReview','variants','returnPolicy','tax','variantItems')->orderBy('id','desc')->where(['status' => 1, 'vendor_id' => $seller->id])->paginate($paginateQty);
        $reviewQty = ProductReview::with('product','user')->where('status',1)->where('product_vendor_id',$seller->id)->count();
        $totalReview = ProductReview::with('product','user')->where('status',1)->where('product_vendor_id',$seller->id)->sum('rating');

        $variantsForSearch = ProductVariant::with('variantItems')->select('name','id')->groupBy('name')->get();
        $shop_page = ShopPage::first();
        $productCategories = Category::where(['status' => 1])->get();
        $brands = Brand::where(['status' => 1])->get();
        $currencySetting = Setting::first();

        return response()->json(['banner'=> $banner, 'seller' => $seller, 'products' => $products, 'reviewQty' => $reviewQty, 'totalReview' => $totalReview, 'variantsForSearch' => $variantsForSearch, 'shop_page' => $shop_page, 'productCategories' => $productCategories, 'brands' => $brands, 'currencySetting' => $currencySetting]);
    }

    public function variantItemsByVariant($name){
        $variantItemsForSearch = ProductVariantItem::with('product','variant')->groupBy('name')->select('name','id')->where('product_variant_name', $name)->get();

        return response()->json(['variantItemsForSearch' => $variantItemsForSearch]);
    }

    public function product(Request $request){
        $variantsForSearch = ProductVariant::with('variantItems')->select('name','id')->groupBy('name')->get();
        $shop_page = ShopPage::first();
        $banner = BreadcrumbImage::where(['id' => 9])->first();
        $productCategories = Category::where(['status' => 1])->get();
        $brands = Brand::where(['status' => 1])->get();
        $paginateQty = CustomPagination::whereId('2')->first()->qty;
        $products = Product::with('category','seller','brand','gallery','specifications','activeReview','variants','returnPolicy','tax','variantItems')->orderBy('id','desc')->where(['status' => 1]);
        if($request->category_id) {
            $products = $products->where('category_id', $request->category_id);
        }
        if($request->sub_category_id) {
            $products = $products->where('sub_category_id', $request->sub_category_id);
        }

        if($request->child_category_id) {
            $products = $products->where('child_category_id', $request->child_category_id);
        }

        if($request->brand_id) {
            $products = $products->where('brand_id', $request->brand_id);
        }

        $products = $products->paginate($paginateQty);
        $seoSetting = SeoSetting::find(9);
        $currencySetting = Setting::first();
        $setting = $currencySetting;

        return response()->json(['banner' => $banner, 'products' => $products, 'productCategories' => $productCategories, 'brands' => $brands, 'shop_page' => $shop_page, 'variantsForSearch' => $variantsForSearch, 'seoSetting' => $seoSetting, 'currencySetting' => $currencySetting, 'setting' => $setting]);
    }

    public function searchProduct(Request $request){
        $paginateQty = CustomPagination::whereId('2')->first()->qty;
        if($request->variantItems){
            $products = Product::with('category','seller','brand','gallery','specifications','activeReview','variants','returnPolicy','tax','variantItems')->whereHas('variantItems', function($query) use ($request){
                $sortArr = [];
                if($request->variantItems){
                    foreach($request->variantItems as $variantItem){
                        $sortArr[] = $variantItem;
                    }
                    $query->whereIn('name', $sortArr);
                }
            })->where('status',1);
        }else{
            $products = Product::with('category','seller','brand','gallery','specifications','reviews','variants','returnPolicy','tax','variantItems')->where('status',1);
        }




        if($request->shorting_id){
            if($request->shorting_id == 1){
                $products = $products->orderBy('id','desc');
            }else if($request->shorting_id == 2){
                $products = $products->orderBy('price','asc');
            }else if($request->shorting_id == 3){
                $products = $products->orderBy('price','desc');
            }
        }else{
            $products = $products->orderBy('id','desc');
        }


        if($request->category) {
            $category = Category::where('slug',$request->category)->first();
            $products = $products->where('category_id', $category->id);
        }

        if($request->sub_category) {
            $sub_category = SubCategory::where('slug',$request->sub_category)->first();
            $products = $products->where('sub_category_id', $sub_category->id);
        }

        if($request->child_category) {
            $child_category = ChildCategory::where('slug',$request->child_category)->first();
            $products = $products->where('child_category_id', $child_category->id);
        }

        if($request->brand) {
            $brand = Brand::where('slug',$request->brand)->first();
            $products = $products->where('brand_id', $brand->id);
        }

        $brandSortArr = [];
        if($request->brands){
            foreach($request->brands as $brand){
                $brandSortArr[] = $brand;
            }
            $products = $products->whereIn('brand_id', $brandSortArr);
        }

        if($request->price_range){
            $price_range = explode(';',$request->price_range);
            $start_price = $price_range[0];
            $end_price = $price_range[1];
            $products = $products->where('price', '>=', $start_price)->where('price', '<=', $end_price);
        }

        if($request->shop_name){
            $slug = $request->shop_name;
            $seller = Vendor::where(['slug' => $slug])->first();
            $products = $products->where('vendor_id', $seller->id);
        }

        if($request->search){
            $products = $products->where('name', 'LIKE', '%'. $request->search. "%")
                                ->orWhere('long_description','LIKE','%'.$request->search.'%');
        }

        $products = $products->paginate($paginateQty);
        $products = $products->appends($request->all());

        $page_view = '';
        if($request->page_view){
            $page_view = $request->page_view;
        }else{
            $page_view = 'grid_view';
        }

        $currencySetting = Setting::first();
        $setting = $currencySetting;

        return response()->json(['products' => $products, 'page_view' => $page_view, 'currencySetting' => $currencySetting, 'setting' => $setting]);

    }

    public function productDetail($slug){
        $product = Product::with('category','seller','brand','gallery','specifications','reviews','variants','returnPolicy','tax','variantItems')->where(['status' => 1, 'slug' => $slug])->first();
        if(!$product){
            $notification = trans('user_validation.Something went wrong');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        $paginateQty = CustomPagination::whereId('5')->first()->qty;
        $productReviews = ProductReview::with('user','product')->where(['status' => 1, 'product_id' =>$product->id])->paginate($paginateQty);
        $totalProductReviewQty = ProductReview::with('user','product')->where(['status' => 1, 'product_id' =>$product->id])->count();
        $recaptchaSetting = GoogleRecaptcha::first();
        $productVariants = ProductVariant::with('variantItems')->where(['status' => 1, 'product_id'=> $product->id])->get();
        $relatedProducts = Product::with('category','seller','brand','gallery','specifications','activeReview','variants','returnPolicy','tax','variantItems')->where(['category_id' => $product->category_id, 'status' => 1])->where('id' , '!=', $product->id)->get()->take(10);
        $currencySetting = Setting::first();
        $banner = BannerImage::whereId('14')->first();
        $setting = Setting::first();
        $defaultProfile = BannerImage::whereId('15')->first();
        $tagArray = json_decode($product->tags);
        $tags = '';
        if($product->tags){
            foreach($tagArray as $index => $tag){
                $tags .= $tag->value.',';
            }
        }

        return response()->json(['product' => $product, 'productReviews' => $productReviews, 'totalProductReviewQty' => $totalProductReviewQty, 'productVariants' => $productVariants, 'recaptchaSetting' => $recaptchaSetting, 'relatedProducts' => $relatedProducts, 'currencySetting' => $currencySetting, 'banner' => $banner, 'setting' => $setting, 'defaultProfile' => $defaultProfile, 'tags' => $tags]);
    }

    public function addToCompare($id){
        $compare_array = [];
        foreach(Cart::instance('compare')->content() as $content){
            $compare_array[] = $content->id;
        }

        if(3 <= Cart::instance('compare')->count()){
            $notification = trans('user_validation.Already 3 items added');
            return response()->json(['status' => 0, 'message' => $notification]);
        }

        if(in_array($id,$compare_array)){
            $notification = trans('user_validation.Already added this item');
            return response()->json(['status' => 0, 'message' => $notification]);
        }else{
            $product = Product::with('tax')->find($id);
            $data=array();
            $data['id'] = $id;
            $data['name'] = 'abc';
            $data['qty'] = 1;
            $data['price'] = 1;
            $data['weight'] = 1;
            $data['options']['product'] = $product;
            Cart::instance('compare')->add($data);
            $notification = trans('user_validation.Item added successfully');
            return response()->json(['status' => 1, 'message' => $notification]);
        }

    }
    public function compare(){
        $banner = BreadcrumbImage::where(['id' => 6])->first();
        $compare_contents = Cart::instance('compare')->content();
        $currencySetting = Setting::first();
        return view('compare', compact('banner','compare_contents','currencySetting'));
    }

    public function removeCompare($id){
        Cart::instance('compare')->remove($id);
        $notification = trans('user_validation.Item remmoved successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }


    public function flashDeal(){
        $banner = BreadcrumbImage::where(['id' => 6])->first();
        $paginateQty = CustomPagination::whereId('2')->first()->qty;
        $products = Product::with('category','seller','brand','gallery','specifications','activeReview','variants','returnPolicy','tax','variantItems')->orderBy('id','desc')->where(['status' => 1, 'is_flash_deal' => 1])->paginate($paginateQty);
        $seoSetting = SeoSetting::find(8);
        $currencySetting = Setting::first();
        $setting = $currencySetting;

        return response()->json(['banner' => $banner, 'products' => $products, 'seoSetting' => $seoSetting, 'currencySetting' => $currencySetting, 'setting' => $setting]);
    }

    public function subscribeRequest(Request $request){
        if($request->email != null){
            $isExist = Subscriber::where('email', $request->email)->count();
            if($isExist == 0){
                $subscriber = new Subscriber();
                $subscriber->email = $request->email;
                $subscriber->verified_token = random_int(100000, 999999);
                $subscriber->save();

                MailHelper::setMailConfig();

                $template=EmailTemplate::where('id',3)->first();
                $message=$template->description;
                $subject=$template->subject;
                Mail::to($subscriber->email)->send(new SubscriptionVerification($subscriber,$message,$subject));

                return response()->json(['status' => 1, 'message' => trans('user_validation.Subscription successfully, please verified your email')]);

            }else{
                return response()->json(['status' => 0, 'message' => trans('user_validation.Email already exist')],403);
            }
        }else{
            return response()->json(['status' => 0, 'message' => trans('user_validation.Email Field is required')],403);
        }
    }

    public function subscriberVerifcation(Request $request, $token){
        $rules = [
            'email'=>'required'
        ];
        $customMessages = [
            'email.required' => trans('user_validation.Email is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $subscriber = Subscriber::where(['verified_token' => $token, 'email' => $request->email])->first();
        if($subscriber){
            $subscriber->verified_token = null;
            $subscriber->is_verified = 1;
            $subscriber->save();
            $notification = trans('user_validation.Email verification successfully');
            return response()->json(['notification' => $notification],200);
        }else{
            $notification = trans('Email or token does not exist');
            return response()->json(['notification' => $notification],400);
        }

    }





















}
