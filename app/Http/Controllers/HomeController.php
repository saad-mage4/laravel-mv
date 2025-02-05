<?php

namespace App\Http\Controllers;

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
use App\Models\AdType;
use App\Models\EmailTemplate;
use App\Models\ProductReview;
use App\Models\Setting;
use App\Models\ContactMessage;
use App\Models\BlogComment;
use App\Models\City;
use App\Models\Country;
use App\Models\CountryState;
use App\Models\ProductVariant;
use App\Models\ProductVariantItem;
use App\Models\GoogleRecaptcha;
use App\Models\Order;
use App\Models\PrivateCategory;
use App\Models\PrivateChildCategoryModel;
use App\Models\PrivateSubCategoryModel;
use App\Models\ProductGallery;
use App\Models\ShopPage;
use App\Models\SeoSetting;
use App\Rules\Captcha;
use Illuminate\Support\Facades\DB;
use Mail;
use Str;
use Session;
use Cart;
use Carbon\Carbon;
use Route;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Swift_TransportException;

class HomeController extends Controller
{
    public function index()
    {
        $brands = Brand::where(['status' => 1])->get();
        $visibilities = HomePageOneVisibility::all();
        $sliders = Slider::orderBy('serial', 'asc')->where(['status' => 1])->get();
        $productCategories = Category::where(['status' => 1])->get();

        $campaign = '';
        $campaign = Campaign::where(['show_homepage' => 1, 'status' => 1])->first();
        $campaignProducts = '';
        if ($campaign) {
            $campaignProducts = CampaignProduct::where(['campaign_id' => $campaign->id, 'show_homepage' => 1])->get();
            $end_time = strtotime($campaign->end_date);
            $start_time  = strtotime(date('Y-m-d'));
        }

        $popularCategory = PopularCategory::first();
        $firstCategory = Category::find($popularCategory->category_id_one);
        $firstCategoryproducts = Product::where('category_id', $popularCategory->category_id_one);
        if ($popularCategory->sub_category_id_one != 0) {
            $firstCategoryproducts = $firstCategoryproducts->where('sub_category_id', $popularCategory->sub_category_id_one);
        }
        if ($popularCategory->child_category_id_one != 0) {
            $firstCategoryproducts = $firstCategoryproducts->where('child_category_id', $popularCategory->child_category_id_one);
        }
        $firstCategoryproducts = $firstCategoryproducts->get()->take($popularCategory->product_qty);

        $secondCategory = Category::find($popularCategory->category_id_two);
        $secondCategoryproducts = Product::where('category_id', $popularCategory->category_id_two);
        if ($popularCategory->sub_category_id_two != 0) {
            $secondCategoryproducts = $secondCategoryproducts->where('sub_category_id', $popularCategory->sub_category_id_two);
        }
        if ($popularCategory->child_category_id_two != 0) {
            $secondCategoryproducts = $secondCategoryproducts->where('child_category_id', $popularCategory->child_category_id_two);
        }
        $secondCategoryproducts = $secondCategoryproducts->get()->take($popularCategory->product_qty);

        $thirdCategory = Category::find($popularCategory->category_id_three);
        $thirdCategoryproducts = Product::where('category_id', $popularCategory->category_id_three);
        if ($popularCategory->sub_category_id_three != 0) {
            $thirdCategoryproducts = $thirdCategoryproducts->where('sub_category_id', $popularCategory->sub_category_id_three);
        }
        if ($popularCategory->child_category_id_three != 0) {
            $thirdCategoryproducts = $thirdCategoryproducts->where('child_category_id', $popularCategory->child_category_id_three);
        }
        $thirdCategoryproducts = $thirdCategoryproducts->get()->take($popularCategory->product_qty);

        $fourthCategory = Category::find($popularCategory->category_id_four);
        $fourthCategoryproducts = Product::where('category_id', $popularCategory->category_id_four);
        if ($popularCategory->sub_category_id_four != 0) {
            $fourthCategoryproducts = $fourthCategoryproducts->where('sub_category_id', $popularCategory->sub_category_id_four);
        }
        if ($popularCategory->child_category_id_four != 0) {
            $fourthCategoryproducts = $fourthCategoryproducts->where('child_category_id', $popularCategory->child_category_id_four);
        }
        $fourthCategoryproducts = $fourthCategoryproducts->get()->take($popularCategory->product_qty);

        $oneColumnBanner = BannerImage::whereId('2')->first();
        $banners  = BannerImage::all();
        $paginateQty = $visibilities->where('id', 7)->first()->qty;
        $flashDealProducts = Product::where(['status' => 1, 'is_flash_deal' => 1])->get();
        $featuredProducts = Product::where(['status' => 1, 'is_featured' => 1])->get()->take($paginateQty);
        $bestProducts = Product::where(['status' => 1, 'is_best' => 1])->get()->take($paginateQty);
        $topProducts = Product::where(['status' => 1, 'is_top' => 1])->get()->take($paginateQty);
        $newProducts = Product::where(['status' => 1, 'new_product' => 1])->get()->take($paginateQty);

        $columnCategories = Category::all();
        $threeColumnCategory = ThreeColumnCategory::first();
        $threeColumnFirstCategoryProducts = Product::where('category_id', $threeColumnCategory->category_id_one);
        if ($threeColumnCategory->sub_category_id_one != 0) {
            $threeColumnFirstCategoryProducts = $threeColumnFirstCategoryProducts->where('sub_category_id', $threeColumnCategory->sub_category_id_one);
        }
        if ($threeColumnCategory->child_category_id_one != 0) {
            $threeColumnFirstCategoryProducts = $threeColumnFirstCategoryProducts->where('child_category_id', $threeColumnCategory->child_category_id_one);
        }
        $threeColumnFirstCategoryProducts = $threeColumnFirstCategoryProducts->get();

        $threeColumnSecondCategoryProducts = Product::where('category_id', $threeColumnCategory->category_id_two);
        if ($threeColumnCategory->sub_category_id_two != 0) {
            $threeColumnSecondCategoryProducts = $threeColumnSecondCategoryProducts->where('sub_category_id', $threeColumnCategory->sub_category_id_two);
        }
        if ($threeColumnCategory->child_category_id_two != 0) {
            $threeColumnSecondCategoryProducts = $threeColumnSecondCategoryProducts->where('child_category_id', $threeColumnCategory->child_category_id_two);
        }
        $threeColumnSecondCategoryProducts = $threeColumnSecondCategoryProducts->get();

        $threeColumnThirdCategoryProducts = Product::where('category_id', $threeColumnCategory->category_id_three);
        if ($threeColumnCategory->sub_category_id_three != 0) {
            $threeColumnThirdCategoryProducts = $threeColumnThirdCategoryProducts->where('sub_category_id', $threeColumnCategory->sub_category_id_three);
        }
        if ($threeColumnCategory->child_category_id_three != 0) {
            $threeColumnThirdCategoryProducts = $threeColumnThirdCategoryProducts->where('child_category_id', $threeColumnCategory->child_category_id_three);
        }
        $threeColumnThirdCategoryProducts = $threeColumnThirdCategoryProducts->get();

        $services = Service::where('status', 1)->get();
        $blogs = Blog::with('admin')->where(['show_homepage' => 1, 'status' => 1])->get();
        $seoSetting = SeoSetting::find(1);
        $currencySetting = Setting::first();
        $setting = $currencySetting;

        return view('index', compact('brands', 'visibilities', 'campaign', 'sliders', 'productCategories', 'campaignProducts', 'popularCategory', 'firstCategory', 'firstCategoryproducts', 'oneColumnBanner', 'banners', 'flashDealProducts', 'featuredProducts', 'bestProducts', 'topProducts', 'newProducts', 'services', 'blogs', 'threeColumnFirstCategoryProducts', 'columnCategories', 'threeColumnCategory', 'threeColumnSecondCategoryProducts', 'threeColumnSecondCategoryProducts', 'threeColumnThirdCategoryProducts', 'seoSetting', 'secondCategory', 'secondCategoryproducts', 'thirdCategory', 'thirdCategoryproducts', 'fourthCategory', 'fourthCategoryproducts', 'currencySetting', 'setting'));
    }

    public function aboutUs()
    {
        $aboutUs = AboutUs::first();
        $seoSetting = SeoSetting::find(2);
        return view('about_us', compact('aboutUs', 'seoSetting'));
    }

    public function Sponsor()
    {
        return view('sponsor');
    }

    public function contactUs()
    {
        $contact = ContactPage::first();
        $recaptchaSetting = GoogleRecaptcha::first();
        $seoSetting = SeoSetting::find(3);
        return view('contact_us', compact('contact', 'recaptchaSetting', 'seoSetting'));
    }

    public function sendContactMessage(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required',
            'message' => 'required',
            'g-recaptcha-response' => new Captcha()
        ];
        $this->validate($request, $rules);

        $setting = Setting::first();
        if ($setting->enable_save_contact_message == 1) {
            $contact = new ContactMessage();
            $contact->name = $request->name;
            $contact->email = $request->email;
            $contact->subject = $request->subject;
            $contact->phone = $request->phone;
            $contact->message = $request->message;
            $contact->save();
        }

        MailHelper::setMailConfig();
        $template = EmailTemplate::where('id', 2)->first();
        $message = $template->description;
        $subject = $template->subject;
        $message = str_replace('{{name}}', $request->name, $message);
        $message = str_replace('{{email}}', $request->email, $message);
        $message = str_replace('{{phone}}', $request->phone, $message);
        $message = str_replace('{{subject}}', $request->subject, $message);
        $message = str_replace('{{message}}', $request->message, $message);



        //! Send Scure mail through hostigner web mail
        try {
            Mail::to($setting->contact_email)->send(new ContactMessageInformation($message, $subject));
        } catch (Swift_TransportException $e) {
            echo $e->getMessage();
        }

        $notification = trans('user_validation.Message send successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function blog()
    {
        $paginateQty = CustomPagination::whereId('1')->first()->qty;
        $blogs = Blog::orderBy('id', 'desc')->where(['status' => 1])->paginate($paginateQty);
        $banner = BreadcrumbImage::where(['id' => 10])->first();
        $seoSetting = SeoSetting::find(6);
        return view('blog', compact('blogs', 'banner', 'seoSetting'));
    }

    public function blogDetail($slug)
    {
        $blog = Blog::where(['status' => 1, 'slug' => $slug])->first();
        $blog->views += 1;
        $blog->save();
        $relatedBlogs = Blog::where(['status' => 1, 'blog_category_id' => $blog->blog_category_id])->where('id', '!=', $blog->id)->get();
        $popularPosts = PopularPost::where(['status' => 1])->get();
        $categories = BlogCategory::where(['status' => 1])->get();
        $recaptchaSetting = GoogleRecaptcha::first();
        return view('blog_detail', compact('blog', 'relatedBlogs', 'popularPosts', 'categories', 'recaptchaSetting'));
    }

    public function blogByCategory($slug)
    {
        $paginateQty = CustomPagination::whereId('1')->first()->qty;
        $category = BlogCategory::where('slug', $slug)->first();
        $blogs = Blog::orderBy('id', 'desc')->where(['status' => 1, 'blog_category_id' => $category->id])->paginate($paginateQty);
        $banner = BreadcrumbImage::where(['id' => 10])->first();
        $seoSetting = SeoSetting::find(6);
        return view('blog', compact('blogs', 'banner', 'seoSetting'));
    }

    public function blogSearch(Request $request)
    {
        $paginateQty = CustomPagination::whereId('1')->first()->qty;
        $blogs = Blog::orderBy('id', 'desc')
            ->where(['status' => 1])
            ->where('title', 'LIKE', '%' . $request->search . '%')
            ->orWhere('description', 'LIKE', '%' . $request->search . '%')
            ->paginate($paginateQty);
        $banner = BreadcrumbImage::where(['id' => 10])->first();
        $seoSetting = SeoSetting::find(6);
        return view('blog', compact('blogs', 'banner', 'seoSetting'));
    }

    public function blogComment(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required',
            'comment' => 'required',
            'g-recaptcha-response' => new Captcha()
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

    public function campaign()
    {
        $banner = BreadcrumbImage::where(['id' => 3])->first();
        $campaigns = Campaign::orderBy('id', 'desc')->where('status', 1)->get();
        $seoSetting = SeoSetting::find(7);
        return view('campaign', compact('banner', 'campaigns', 'seoSetting'));
    }

    public function campaignDetail($slug)
    {
        $banner = BreadcrumbImage::where(['id' => 3])->first();
        $campaign = Campaign::where(['status' => 1, 'slug' => $slug])->first();
        $campaignProducts = $campaign->products;
        $currencySetting = Setting::first();

        $bannerOne = BannerImage::whereId('11')->first();
        $bannerTwo = BannerImage::whereId('12')->first();
        $setting = Setting::first();
        return view('campaign_detail', compact('banner', 'campaign', 'campaignProducts', 'currencySetting', 'bannerOne', 'bannerTwo', 'setting'));
    }

    public function brand()
    {
        $paginateQty = CustomPagination::whereId('3')->first()->qty;
        $brands = Brand::orderBy('id', 'desc')->where('status', 1)->paginate($paginateQty);
        $banner = BreadcrumbImage::where(['id' => 1])->first();
        $seoSetting = SeoSetting::find(4);
        return view('brand', compact('brands', 'banner', 'seoSetting'));
    }

    public function trackOrder()
    {
        $banner = BreadcrumbImage::where(['id' => 7])->first();
        return view('track_order', compact('banner'));
    }

    public function trackOrderResponse($id)
    {
        if (!$id) {
            $message = trans('user_validation.Order id is required');
            return response()->json(['status' => 0, 'message' => $message]);
        }
        $order = Order::where('order_id', $id)->first();
        if ($order) {
            return view('ajax_track_order', compact('order'));
        } else {
            $message = trans('user_validation.Order not found');
            return response()->json(['status' => 0, 'message' => $message]);
        }
    }

    public function faq()
    {
        $banner = BreadcrumbImage::where(['id' => 4])->first();
        $faqs = FAQ::orderBy('id', 'desc')->where('status', 1)->get();
        return view('faq', compact('banner', 'faqs'));
    }

    public function customPage($slug)
    {
        $page = CustomPage::where(['slug' => $slug, 'status' => 1])->first();
        return view('custom_page', compact('page'));
    }

    public function termsAndCondition()
    {
        $terms_conditions = TermsAndCondition::first();
        return view('terms_and_conditions', compact('terms_conditions'));
    }

    public function privacyPolicy()
    {
        $privacyPolicy = TermsAndCondition::first();
        return view('privacy_policy', compact('privacyPolicy'));
    }

    //! Sellers List !
    public function seller()
    {
        $banner = BreadcrumbImage::where(['id' => 8])->first();
        $sellers = Vendor::orderBy('id', 'desc')->where('status', 1)->get();
        $productReviews = ProductReview::all();
        $seoSetting = SeoSetting::find(5);
        return view('seller', compact('banner', 'sellers', 'productReviews', 'seoSetting'));
    }

    public function sellerDetail(Request $request)
    {
        $slug = $request->shop_name;
        $banner = BreadcrumbImage::where(['id' => 8])->first();
        $seller = Vendor::where(['status' => 1, 'slug' => $slug])->first();
        $paginateQty = CustomPagination::whereId('2')->first()->qty;
        $products = Product::orderBy('id', 'desc')->where(['status' => 1, 'vendor_id' => $seller->id])->paginate($paginateQty);
        $reviewQty = ProductReview::where('status', 1)->where('product_vendor_id', $seller->id)->count();
        $totalReview = ProductReview::where('status', 1)->where('product_vendor_id', $seller->id)->sum('rating');

        $variantsForSearch = ProductVariant::select('name', 'id')->groupBy('name')->get();
        $shop_page = ShopPage::first();
        $productCategories = Category::where(['status' => 1])->get();
        $brands = Brand::where(['status' => 1])->get();
        $currencySetting = Setting::first();
        return view('seller_detail', compact('banner', 'seller', 'products', 'reviewQty', 'totalReview', 'variantsForSearch', 'shop_page', 'productCategories', 'brands', 'currencySetting'));
    }


    //! seller_used_detail

    public function sellerUsedDetail(Request $request)
    {
        $slug = $request->shop_name;
        $banner = BreadcrumbImage::where(['id' => 8])->first();
        $seller = Vendor::where(['status' => 1, 'slug' => $slug])->first();
        // dd($seller);
        $paginateQty = CustomPagination::whereId('2')->first()->qty;
        $products = Product::orderBy('id', 'desc')->where(['status' => 1, 'vendor_id' => $seller->id])->paginate($paginateQty);
        $reviewQty = ProductReview::where('status', 1)->where('product_vendor_id', $seller->id)->count();
        $totalReview = ProductReview::where('status', 1)->where('product_vendor_id', $seller->id)->sum('rating');

        $variantsForSearch = ProductVariant::select('name', 'id')->groupBy('name')->get();
        $shop_page = ShopPage::first();
        $productCategories = Category::where(['status' => 1])->get();
        $brands = Brand::where(['status' => 1])->get();
        $currencySetting = Setting::first();
        $defaultProfile = BannerImage::whereId('15')->first();
        return view('seller_used_detail', compact('banner', 'defaultProfile', 'seller', 'products', 'reviewQty', 'totalReview', 'variantsForSearch', 'shop_page', 'productCategories', 'brands', 'currencySetting'));
    }

    public function product(Request $request)
    {
        // dd($request);
        $variantsForSearch = ProductVariant::select('name', 'id')->groupBy('name')->get();
        $shop_page = ShopPage::first();
        $banner = BreadcrumbImage::where(['id' => 9])->first();
        $productCategories = Category::where(['status' => 1])->get();
        $brands = Brand::where(['status' => 1])->get();
        $paginateQty = CustomPagination::whereId('2')->first()->qty;
        $products = Product::orderBy('id', 'desc')->where(['status' => 1]);
        if ($request->category_id) {
            $products = $products->where('category_id', $request->category_id);
        }
        if ($request->sub_category_id) {
            $products = $products->where('sub_category_id', $request->sub_category_id);
        }

        if ($request->child_category_id) {
            $products = $products->where('child_category_id', $request->child_category_id);
        }

        if ($request->brand_id) {
            $products = $products->where('brand_id', $request->brand_id);
        }

        $products = $products->paginate($paginateQty);
        $seoSetting = SeoSetting::find(9);
        $currencySetting = Setting::first();
        $setting = $currencySetting;
        return view('product', compact('banner', 'products', 'productCategories', 'brands', 'shop_page', 'variantsForSearch', 'seoSetting', 'currencySetting', 'setting'));
    }

    //* used Product (Private Seller )
    public function Used_Products(Request $request)
    {
        $variantsForSearch = ProductVariant::select('name', 'id')->groupBy('name')->get();
        $shop_page = ShopPage::first();
        $banner = BreadcrumbImage::where(['id' => 9])->first();
        $productCategories = Category::where(['status' => 1])->get();
        $productPrivateCategories = PrivateCategory::where(['status' => 1])->get();
        $productPrivateCategories = PrivateCategory::where('status', 1)
            ->with(['private_subCategories.childCategories'])
            ->get();

        //! For Private Category
        // $productPrivateCategories = PrivateCategory::where('status', 1)
        // ->whereHas('private_subCategories', function ($query) {
        //     $query->whereHas('childCategories');
        // })
        //     ->with(['private_subCategories.childCategories'])
        //     ->get();
        $brands = Brand::where(['status' => 1])->get();
        // Ads Type
        $ads = AdType::where(['status' => 1])->get();
        $paginateQty = CustomPagination::whereId('2')->first()->qty;
        // $products = Product::where(['status' => 1])->orderBy('id', 'desc');
        // $product_status = DB::table('products')->where('status', '1')->where('private_ad', 'AdminPrivateProduct')->get();

        // if ($product_status) {
        //     $products = DB::table('products')
        //         ->where('status', '1')
        //         ->where('private_ad', 'AdminPrivateProduct')
        //         ->orderBy('id', 'desc')
        //         ->paginate($paginateQty);
        // } else {
        // }


        $products = DB::table('products')->where('status', '1')->orderBy('id', 'desc'); //->paginate($paginateQty)

        //! Query for admin and private seller products
        // $products = Product::where('status', 1)
        //     ->where(function ($query) {
        //         $query->where('seller_type', 'Private')
        //             ->orWhere('seller_type', 'AdminPrivate');
        //     })
        //     ->orderBy('id', 'desc');

        // dd($products);



        // $products = Product::where(['status' => 1])->orderBy('created_at', 'desc')->get();
        if ($request->category_id) {
            $products = DB::table('products')->where('category_id', $request->category_id);
        }

        // if ($request->sub_category_id) {
        //     $products = $products->where('sub_category_id', $request->sub_category_id);
        // }

        // if ($request->child_category_id) {
        //     $products = $products->where('child_category_id', $request->child_category_id);
        // }

        //! For Private Category Data

        if ($request->private_category_id) {
            $products = DB::table('products')->where('private_category_id', $request->private_category_id);
        }

        if ($request->private_sub_category_id) {
            $products = $products->where('private_sub_category_id', $request->private_sub_category_id);
        }

        if ($request->private_child_category_id) {
            $products = $products->where('private_child_category_id', $request->private_child_category_id);
        }

        //! For Private Category Data End

        if ($request->brand_id) {
            $products = DB::table('products')->where('brand_id', $request->brand_id);
        }
        if ($request->private_ad_type) {
            $products = DB::table('products')->where('private_ad_type', $request->private_ad_type);
        }

        //! Paginate the results
        $products = $products->paginate($paginateQty);

        // $products = $products->paginate($paginateQty);
        $seoSetting = SeoSetting::find(9);
        $currencySetting = Setting::first();
        $setting = $currencySetting;
        return view('used_products', compact('banner', 'products', 'productCategories', 'productPrivateCategories', 'brands', 'ads', 'shop_page', 'variantsForSearch', 'seoSetting', 'currencySetting', 'setting'));
    }


    public function searchProduct(Request $request)
    {
        $paginateQty = CustomPagination::whereId('2')->first()->qty;
        if ($request->variantItems) {
            $products = Product::whereHas('variantItems', function ($query) use ($request) {
                $sortArr = [];
                if ($request->variantItems) {
                    foreach ($request->variantItems as $variantItem) {
                        $sortArr[] = $variantItem;
                    }
                    $query->whereIn('name', $sortArr);
                }
            })->where('status', 1)
                ->whereIn('seller_type', ['AdminPublic', 'Public']);
            // ->where('seller_type', 'Public');
        } else {
            $products = Product::where('status', 1)
                ->whereIn('seller_type', ['AdminPublic', 'Public']);
            // ->where('seller_type', 'Public');
        }


        if ($request->shorting_id) {
            if ($request->shorting_id == 1) {
                $products = $products->orderBy('id', 'desc');
            } else if ($request->shorting_id == 2) {
                $products = $products->orderBy('price', 'asc');
            } else if ($request->shorting_id == 3) {
                $products = $products->orderBy('price', 'desc');
            }
        } else {
            $products = $products->orderBy('id', 'desc');
        }


        if ($request->category) {
            $category = Category::where('slug', $request->category)->first();
            $products = $products->where('category_id', $category->id);
        }


        if ($request->sub_category) {
            $sub_category = SubCategory::where('slug', $request->sub_category)->first();
            $products = $products->where('sub_category_id', $sub_category->id);
        }

        if ($request->child_category) {
            $child_category = ChildCategory::where('slug', $request->child_category)->first();
            $products = $products->where('child_category_id', $child_category->id);
        }

        if ($request->brand) {
            $brand = Brand::where('slug', $request->brand)->first();
            $products = $products->where('brand_id', $brand->id);
        }

        $brandSortArr = [];
        if ($request->brands) {
            foreach ($request->brands as $brand) {
                $brandSortArr[] = $brand;
            }
            $products = $products->whereIn('brand_id', $brandSortArr);
        }

        if ($request->price_range) {
            $price_range = explode(';', $request->price_range);
            $start_price = $price_range[0];
            $end_price = $price_range[1];
            $products = $products->where('price', '>=', $start_price)->where('price', '<=', $end_price);
        }

        if ($request->shop_name) {
            $slug = $request->shop_name;
            $seller = Vendor::where(['slug' => $slug])->first();
            $products = $products->where('vendor_id', $seller->id);
        }

        if ($request->search) {
            $products = $products->where('name', 'LIKE', '%' . $request->search . "%")
                ->orWhere('long_description', 'LIKE', '%' . $request->search . '%');
        }

        $products = $products->paginate($paginateQty);
        $products = $products->appends($request->all());

        $page_view = '';
        if ($request->page_view) {
            $page_view = $request->page_view;
        } else {
            $page_view = 'grid_view';
        }

        $currencySetting = Setting::first();
        $setting = $currencySetting;
        return view('ajax_products', compact('products', 'page_view', 'currencySetting', 'setting'));
    }

    //! new Way (Default Ajax Load to show the Product in the Used Product Page)
    public function searchUsedProduct(Request $request)
    {

        $paginateQty = CustomPagination::whereId('2')->first()->qty;

        //! Initialize the products query
        // $products = Product::where('products.status', 1)
        //     ->where('products.seller_type', 'Private')
        //     ->join('vendors', 'products.vendor_id', '=', 'vendors.id')
        //     ->select('products.*', 'vendors.phone');

        //! For Admin Private Product Selections
        $products = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereIn('products.seller_type', ['AdminPrivate', 'Private'])
            ->leftJoin(
                'vendors',
                'products.vendor_id',
                '=',
                'vendors.id'
            )
            // ->orWhere('products.vendor_id', '=', 0)
            ->select('products.*', 'vendors.phone', 'categories.name as CategoryName', 'categories.slug as categorySlug')
            ->orderBy('id', 'desc')
            ->get();

        /**
         * * Company & Private Categories Work
         * TODO: category , sub_category, child_category, private_category , private_sub_category, private_child_category filter
         */


        //! Company Category Filters
        if ($request->category) {
            $category = Category::where('slug', $request->category)->first();
            $products = $products->where('category_id', $category->id);
        }

        //! for Private Category Filter

        if ($request->private_category) {
            $category = PrivateCategory::where('slug', $request->private_category)->first();
            $products = $products->where('private_category_id', $category->id);
        }

        if ($request->private_sub_category) {
            $sub_category = PrivateSubCategoryModel::where('slug', $request->private_sub_category)->first();
            $products = $products->where('private_sub_category_id', $sub_category->id);
        }

        if ($request->private_child_category) {
            $child_category = PrivateChildCategoryModel::where('slug', $request->private_child_category)->first();
            // $products = $products->whepaginateQtynd;
            $products = $products->where('private_child_category_id', $child_category->id);
        }



        //! Brand Filter
        if ($request->brands) {
            $brandIds = $request->brands;
            $products = $products->whereIn('brand_id', $brandIds);
        }

        //! AdType Filter
        if ($request->AdType) {
            $products = $products->whereIn('private_ad_type', $request->AdType);
        }


        //! Price Range Filter
        if ($request->price_range) {
            $price_range = explode(';', $request->price_range);
            $start_price = $price_range[0];
            $end_price = $price_range[1];
            $products = $products->whereBetween('price', [$start_price, $end_price]);
        }

        //! Vendor Filter
        if ($request->shop_name) {
            $slug = $request->shop_name;
            $seller = Vendor::where(['slug' => $slug])->first();
            $products = $products->where('vendor_id', $seller->id);
        }

        //! Search Filter
        if ($request->search) {
            $products = $products->where(function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->search . "%")
                    ->orWhere('long_description', 'LIKE', '%' . $request->search . '%');
            });
        }



        //! Page View Filter
        $page_view = $request->page_view ?? 'grid_view';

        //! Get currency settings
        $currencySetting = Setting::first();
        $setting = $currencySetting;
        $ads = AdType::where(['status' => 1])->get();
        //! Pagination
        // $products = $products->paginate($paginateQty)->appends($request->all());

        return view('ajax_used_products', compact('products', 'ads', 'page_view', 'currencySetting', 'setting'));
    }


    //! Custom Search function for used product private page

    // * Search Filter

    //! For Private Search Ajax Page
    public function PrivateStateByCountry($id = 1)
    {
        $states = CountryState::where(['status' => 1, 'country_id' => $id])->get();
        $response = '<option value="">County</option>';
        if ($states->count() > 0) {
            foreach ($states as $state) {
                $response .= "<option value=" . $state->id . " data-name=" . $state->name . ">" . $state->name . "</option>";
            }
        }
        return response()->json(['states' => $response]);
    }

    public function PrivateCityByState($id)
    {
        $cities = City::where(['status' => 1, 'country_state_id' => $id])->get();
        $response = '<option value="">City</option>';
        if ($cities->count() > 0) {
            foreach ($cities as $city) {
                $response .= "<option  value=" . $city->id . " data-name=" . $city->name . ">" . $city->name . "</option>";
            }
        }
        return response()->json(['cities' => $response]);
    }

    //! searchPrivateCustomUsedProduct in Private Page
    public function searchPrivateCustomUsedProduct(Request $request)
    {
        $data = $request->values;
        $paginateQty = CustomPagination::whereId('2')->first()->qty;
        $ads = AdType::all();

        /**
         * * Join the Tables beacuse user watch if its not login
         * TODO: products , categories, vendors, brands , pagination, price filter
         */
        // $products = DB::table('products')
        // ->join('categories', 'products.category_id', '=', 'categories.id')
        // ->join('vendors', 'products.vendor_id', '=', 'vendors.id')
        // ->select('products.*', 'vendors.phone', 'categories.name as CategoryName', 'categories.slug as categorySlug')->where('seller_type', 'Private')->orderBy('id', 'desc')->paginate($paginateQty)->appends($request->all());

        //! page View Filter
        $page_view = $request->page_view ?? 'grid_view';



        $productsQuery = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereIn('seller_type', ['AdminPrivate', 'Private'])
            ->leftJoin(
                'vendors',
                'products.vendor_id',
                '=',
                'vendors.id'
            )
            // ->orWhere('products.vendor_id', '=', 0)
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->select('products.*', 'vendors.phone', 'categories.name as CategoryName', 'categories.slug as categorySlug');
        // ->get();
        // ->where('seller_type', 'Private');

        $state = $data['state'];
        $city = $data['city'];
        $input_value = $data['input_value'];

        //! New change for custom search
        if (!empty($data['state'])) {
            $productsQuery->where('vendors.state', $data['state']);
        }

        if (!empty($data['city'])) {
            $productsQuery->where('vendors.city', $data['city']);
        }

        if (!empty($data['input_value'])) {
            $productsQuery->where('products.name', 'like', '%' . $data['input_value'] . '%');
        }




        $products = $productsQuery->orderBy('id', 'desc')
            ->paginate($paginateQty)->appends($request->all());



        $currencySetting = Setting::first();
        $setting = $currencySetting;


        return view(
            'ajax_used_products',
            compact('products', 'page_view', 'ads', 'currencySetting', 'setting')
        );
    }

    public function productDetail($slug)
    {
        $product = Product::where(['status' => 1, 'slug' => $slug])->first();

        if (!$product) {
            $notification = trans('user_validation.Something went wrong');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->back()->with($notification);
        }
        $paginateQty = CustomPagination::whereId('5')->first()->qty;
        $productReviews = ProductReview::where(['status' => 1, 'product_id' => $product->id])->paginate($paginateQty);
        $totalProductReviewQty = ProductReview::where(['status' => 1, 'product_id' => $product->id])->count();
        $recaptchaSetting = GoogleRecaptcha::first();
        $productVariants = ProductVariant::where(['status' => 1, 'product_id' => $product->id])->get();
        $relatedProducts = Product::where(['category_id' => $product->category_id, 'status' => 1])->where('id', '!=', $product->id)->get()->take(10);
        $currencySetting = Setting::first();
        $banner = BannerImage::whereId('14')->first();
        $setting = Setting::first();
        $defaultProfile = BannerImage::whereId('15')->first();
        $tagArray = json_decode($product->tags);
        $tags = '';
        if ($product->tags) {
            foreach ($tagArray as $index => $tag) {
                $tags .= $tag->value . ',';
            }
        }
        return view('product_detail', compact('product', 'productReviews', 'totalProductReviewQty', 'productVariants', 'recaptchaSetting', 'relatedProducts', 'currencySetting', 'banner', 'setting', 'defaultProfile', 'tags'));
    }

    //! Private Products Details
    public function productUsedDetail($slug)
    {
        $product = Product::whereIn('seller_type', ['AdminPrivate', 'Private'])
            ->where(['status' => 1, 'slug' => $slug])->first();
        if (!$product) {
            $notification = trans('user_validation.Something went wrong');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->back()->with($notification);
        }
        $paginateQty = CustomPagination::whereId('5')->first()->qty;
        $productReviews = ProductReview::where(['status' => 1, 'product_id' => $product->id])->paginate($paginateQty);
        $totalProductReviewQty = ProductReview::where(['status' => 1, 'product_id' => $product->id])->count();
        $recaptchaSetting = GoogleRecaptcha::first();
        $productVariants = ProductVariant::where(['status' => 1, 'product_id' => $product->id])->get();
        $relatedProducts = Product::where(['private_category_id' => $product->private_category_id, 'status' => 1])->where('id', '!=', $product->id)->get()->take(10);
        $currencySetting = Setting::first();
        $banner = BannerImage::whereId('14')->first();
        $setting = Setting::first();
        $defaultProfile = BannerImage::whereId('15')->first();
        $tagArray = json_decode($product->tags);
        $tags = '';
        if ($product->tags) {
            foreach ($tagArray as $index => $tag) {
                $tags .= $tag->value . ',';
            }
        }

        /**
         * TODO : Session Expire
         * ! For that if the user is !login (session logout)
         */

        // Use DB query with proper joins
        $product = DB::table('products')
            ->whereIn('seller_type', ['AdminPrivate', 'Private'])
            ->where(['products.status' => 1, 'products.slug' => $slug])
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('vendors', 'products.vendor_id', '=', 'vendors.id')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->leftJoin('cities', 'vendors.city', '=', 'cities.id')
            ->leftJoin('country_states', 'vendors.state', '=', 'country_states.id')
            ->select(
                'products.*',
                'vendors.phone',
                'categories.name as CategoryName',
                'categories.slug as categorySlug',
                'brands.name as brandName',
                'brands.slug as brandSlug',
                'vendors.firstName',
                'vendors.lastName',
                'vendors.shop_name',
                'vendors.address',
                'vendors.city',
                'vendors.country',
                'vendors.state',
                'vendors.email',
                'vendors.description as vendorDescription',
                'vendors.banner_image as Vendor_banner',
                'vendors.slug as Vendor_Slug',
                'cities.name as City_Name',
                'country_states.name as State_Name'
            )
            ->first();

        $currencySetting = Setting::first();
        $setting = $currencySetting;
        $gallery = ProductGallery::where('product_id', $product->id)->where('status', 1)->get();
        // dd($gallery);
        $ads = AdType::where(['status' => 1])->get();

        $countryName = Country::where('id', $product->private_country)->value('name');
        $stateName = CountryState::where('id', $product->private_state)->value('name');
        $cityName = City::where('id', $product->private_city)->value('name');


        return view('product_used_detail', compact(
            'product',
            'ads',
            'productReviews',
            'totalProductReviewQty',
            'productVariants',
            'recaptchaSetting',
            'relatedProducts',
            'currencySetting',
            'banner',
            'setting',
            'defaultProfile',
            'tags',
            'gallery',
            'countryName',
            'stateName',
            'cityName'
        ));
    }

    public function addToCompare($id)
    {
        $compare_array = [];
        foreach (Cart::instance('compare')->content() as $content) {
            $compare_array[] = $content->id;
        }

        if (3 <= Cart::instance('compare')->count()) {
            $notification = trans('user_validation.Already 3 items added');
            return response()->json(['status' => 0, 'message' => $notification]);
        }

        if (in_array($id, $compare_array)) {
            $notification = trans('user_validation.Already added this item');
            return response()->json(['status' => 0, 'message' => $notification]);
        } else {
            $product = Product::with('tax')->find($id);
            $data = array();
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
    public function compare()
    {
        $banner = BreadcrumbImage::where(['id' => 6])->first();
        $compare_contents = Cart::instance('compare')->content();
        $currencySetting = Setting::first();
        return view('compare', compact('banner', 'compare_contents', 'currencySetting'));
    }

    public function removeCompare($id)
    {
        Cart::instance('compare')->remove($id);
        $notification = trans('user_validation.Item remmoved successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }


    public function flashDeal()
    {
        $banner = BreadcrumbImage::where(['id' => 6])->first();
        $paginateQty = CustomPagination::whereId('2')->first()->qty;
        $products = Product::orderBy('id', 'desc')->where(['status' => 1, 'is_flash_deal' => 1])->paginate($paginateQty);
        $seoSetting = SeoSetting::find(8);
        $currencySetting = Setting::first();
        $setting = $currencySetting;
        return view('flash_deal', compact('banner', 'products', 'seoSetting', 'currencySetting', 'setting'));
    }

    public function subscribeRequest(Request $request)
    {
        if ($request->email != null) {
            $isExist = Subscriber::where('email', $request->email)->count();
            if ($isExist == 0) {
                $subscriber = new Subscriber();
                $subscriber->email = $request->email;
                $subscriber->verified_token = Str::random(25);
                $subscriber->save();

                MailHelper::setMailConfig();

                $template = EmailTemplate::where('id', 3)->first();
                $message = $template->description;
                $subject = $template->subject;

                //! Send Scure mail through hostigner web mail
                try {
                    Mail::to($subscriber->email)->send(new SubscriptionVerification($subscriber, $message, $subject));
                } catch (Swift_TransportException $e) {
                    echo $e->getMessage();
                }

                return response()->json(['status' => 1, 'message' => trans('user_validation.Subscription successfully, please verified your email')]);
            } else {
                return response()->json(['status' => 0, 'message' => trans('user_validation.Email already exist')]);
            }
        } else {
            return response()->json(['status' => 0, 'message' => trans('user_validation.Email Field is required')]);
        }
    }

    public function subscriberVerifcation($token)
    {
        $subscriber = Subscriber::where('verified_token', $token)->first();
        if ($subscriber) {
            $subscriber->verified_token = null;
            $subscriber->is_verified = 1;
            $subscriber->save();
            $notification = trans('user_validation.Email verification successfully');
            $notification = array('messege' => $notification, 'alert-type' => 'success');
            return redirect()->route('home')->with($notification);
        } else {
            $notification = trans('user_validation.Invalid token');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->route('home')->with($notification);
        }
    }
}
