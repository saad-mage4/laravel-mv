<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdType;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ChildCategory;
use App\Models\ProductGallery;
use App\Models\Brand;
use App\Models\ProductTax;
use App\Models\ReturnPolicy;
use App\Models\ProductSpecificationKey;
use App\Models\ProductSpecification;
use App\Models\OrderProduct;
use App\Models\ProductVariant;
use App\Models\ProductVariantItem;
use App\Models\CampaignProduct;
use App\Models\OrderProductVariant;
use App\Models\PrivateCategory;
use App\Models\PrivateChildCategoryModel;
use App\Models\PrivateSubCategoryModel;
use App\Models\ProductReport;
use App\Models\ProductReview;
use App\Models\Wishlist;
use App\Models\Setting;
use App\Rules\NotSvg;
use Image;
use File;
use Illuminate\Support\Facades\DB;
use Str;

class PrivateProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $products = Product::with('private_categories')->where(['vendor_id' => 0])->orderBy('id', 'desc')->get();
        $orderProducts = OrderProduct::all();
        $setting = Setting::first();
        $ads = AdType::where(['status' => 1])->get();
        return view('admin.private_product', compact('products', 'orderProducts', 'setting'));
    }

    public function create()
    {
        $categories = PrivateCategory::all();
        $brands = Brand::all();
        $ads = AdType::all();
        return view('admin.create_private_product', compact('categories', 'brands', 'ads'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'slug' => 'required|unique:products',
            'thumb_image' =>  ['required', 'image', new NotSvg()],
            'banner_image' =>  ['required', 'image', new NotSvg()],
            'category' => 'required',
            'long_description' => 'required',
            'brand' => 'required',
            'price' => 'required|numeric',
            'private_ad_type' => 'required',
            'status' => 'required'
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'name.unique' => trans('admin_validation.Name is required'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
            'category.required' => trans('admin_validation.Category is required'),
            'thumb_image.required' => trans('admin_validation.thumbnail is required'),
            'banner_image.required' => trans('admin_validation.Banner is required'),
            'long_description.required' => trans('admin_validation.Long description is required'),
            'brand.required' => trans('admin_validation.Brand is required'),
            'price.required' => trans('admin_validation.Price is required'),
            'private_ad_type.required' => 'Add Type is required',
            'status.required' => trans('admin_validation.Status is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $product = new Product();
        if ($request->thumb_image) {
            $extention = $request->thumb_image->getClientOriginalExtension();
            $image_name = Str::slug($request->name) . date('-Y-m-d-h-i-s-') . rand(999, 9999) . '.' . $extention;
            $image_name = 'uploads/custom-images/' . $image_name;
            Image::make($request->thumb_image)
                ->save(public_path() . '/' . $image_name);
            $product->thumb_image = $image_name;
        }

        if ($request->banner_image) {
            $extention = $request->banner_image->getClientOriginalExtension();
            $banner_name = 'product-banner' . date('-Y-m-d-h-i-s-') . rand(999, 9999) . '.' . $extention;
            $banner_name = 'uploads/custom-images/' . $banner_name;
            Image::make($request->banner_image)
                ->save(public_path() . '/' . $banner_name);
            $product->banner_image = $banner_name;
        }




        $product->short_name = "";
        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->category_id = 25;
        $product->sub_category_id = 0;
        $product->child_category_id = 0;
        $product->private_category_id = $request->category;
        $product->private_sub_category_id = $request->sub_category ?? 0;
        $product->private_child_category_id = $request->child_category ?? 0;
        $product->brand_id = $request->brand;
        $product->sku = null;
        $product->price = $request->price;
        $product->offer_price = 0;
        $product->qty = 0;
        $product->short_description = '';
        $product->long_description = $request->long_description;
        $product->video_link = null;
        $product->tags = null;
        $product->tax_id = 0;
        $product->is_warranty = 0;
        $product->is_return = 0;
        $product->return_policy_id = 0;
        $product->seller_type = "AdminPrivate";
        $product->private_ad_type = $request->private_ad_type;
        $product->private_phone = $request->private_phone;
        $product->private_country = $request->private_country;
        $product->private_state = $request->private_state;
        $product->private_city = $request->private_city;
        $product->status = $request->status;

        $product->is_undefine = 1;
        $product->is_specification = 0;
        $product->seo_title = $request->name;
        $product->seo_description = $request->name;
        $product->save();

        $getCurrentProdId = DB::table('products')->select('id')->orderByDesc('id')->first();

        //! Image Gallery Logic
        if ($request->images) {
            foreach ($request->images as $index => $image) {
                $extention = $image->getClientOriginalExtension();
                $image_name = 'Gallery' . date('-Y-m-d-h-i-s-') . rand(999, 9999) . '.' . $extention;
                $image_name = 'uploads/custom-images/' . $image_name;
                Image::make($image)
                    ->save(public_path() . '/' . $image_name);
                $gallery = new ProductGallery();
                $gallery->product_id =
                    $getCurrentProdId->id;
                $gallery->image = $image_name;
                $gallery->save();
            }
        }

        $notification = trans('admin_validation.Created Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('admin.private_product.index')->with($notification);
    }

    public function show(Product $product)
    {
        //
    }

    public function edit($id)
    {
        $product = Product::find($id);
        $categories = PrivateCategory::all();
        $subCategories = PrivateSubCategoryModel::all();
        $childCategories = PrivateChildCategoryModel::all();
        $gallery = ProductGallery::where('product_id', $product->id)->get();
        $brands = Brand::all();
        $ads = AdType::all();
        return view('admin.edit_private_product', compact('categories', 'brands',  'product', 'subCategories', 'childCategories', 'ads', 'gallery'));
    }

    public function update(Request $request, $id)
    {

        $product = Product::find($id);
        $gallery = ProductGallery::where('product_id', $product->id)->get();
        $rules = [
            'name' => 'required',
            'slug' => 'required|unique:products,slug,' . $product->id,
            // 'thumb_image' => 'required',
            // 'banner_image' => 'required',
            'category' => 'required',
            'long_description' => 'required',
            'brand' => 'required',
            'price' => 'required|numeric',
            'private_ad_type' => 'required',
            'status' => 'required'
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'name.unique' => trans('admin_validation.Name is required'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
            'category.required' => trans('admin_validation.Category is required'),
            'thumb_image.required' => trans('admin_validation.thumbnail is required'),
            'banner_image.required' => trans('admin_validation.Banner is required'),
            'long_description.required' => trans('admin_validation.Long description is required'),
            'brand.required' => trans('admin_validation.Brand is required'),
            'price.required' => trans('admin_validation.Price is required'),
            'private_ad_type.required' => 'Add Type is required',
            'status.required' => trans('admin_validation.Status is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        if ($request->thumb_image) {
            $old_thumbnail = $product->thumb_image;
            $extention = $request->thumb_image->getClientOriginalExtension();
            $image_name = Str::slug($request->name) . date('-Y-m-d-h-i-s-') . rand(999, 9999) . '.' . $extention;
            $image_name = 'uploads/custom-images/' . $image_name;
            Image::make($request->thumb_image)
                ->save(public_path() . '/' . $image_name);
            $product->thumb_image = $image_name;
            $product->save();
            if ($old_thumbnail) {
                if (File::exists(public_path() . '/' . $old_thumbnail)) unlink(public_path() . '/' . $old_thumbnail);
            }
        }

        if ($request->banner_image) {
            $old_banner = $product->banner_image;
            $extention = $request->banner_image->getClientOriginalExtension();
            $banner_name = 'product-banner' . date('-Y-m-d-h-i-s-') . rand(999, 9999) . '.' . $extention;
            $banner_name = 'uploads/custom-images/' . $banner_name;
            Image::make($request->banner_image)
                ->save(public_path() . '/' . $banner_name);
            $product->banner_image = $banner_name;
            $product->save();
            if ($old_banner) {
                if (File::exists(public_path() . '/' . $old_banner)) unlink(public_path() . '/' . $old_banner);
            }
        }


        $product->short_name = "";
        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->category_id = 25;
        $product->sub_category_id = 0;
        $product->child_category_id =  0;
        $product->private_category_id = $request->category;
        $product->private_sub_category_id = $request->sub_category ?? 0;
        $product->private_child_category_id = $request->child_category ?? 0;
        $product->brand_id = $request->brand;
        $product->sku = null;
        $product->price = $request->price;
        $product->offer_price = 0;
        $product->qty = 0;
        $product->short_description = '';
        $product->long_description = $request->long_description;
        $product->video_link = null;
        $product->tags = null;
        $product->tax_id = 0;
        $product->is_warranty = 0;
        $product->is_return = 0;
        $product->return_policy_id = 0;
        $product->seller_type = "AdminPrivate";
        $product->private_ad_type = $request->private_ad_type;
        $product->private_phone = $request->private_phone;
        $product->private_country = $request->private_country;
        $product->private_state = $request->private_state;
        $product->private_city = $request->private_city;
        $product->status = $request->status;

        $product->is_undefine = 1;
        $product->is_specification = 0;
        $product->seo_title = $request->name;
        $product->seo_description = $request->name;
        $product->save();

        // $getCurrentProdId = DB::table('products')->select('id')->orderByDesc('id')->first();
        //! Image Gallery Logic
        if ($request->images) {
            $old_gallery =   $gallery;
            if ($old_gallery) {
                if (File::exists(public_path() . '/' . $old_gallery)) unlink(public_path() . '/' . $old_gallery);
                // $old_gallery->save();
            } else {
                foreach ($request->images as $index => $image) {
                    $extention = $image->getClientOriginalExtension();
                    $image_name = 'Gallery' . date('-Y-m-d-h-i-s-') . rand(999, 9999) . '.' . $extention;
                    $image_name = 'uploads/custom-images/' . $image_name;
                    Image::make($image)
                        ->save(public_path() . '/' . $image_name);
                    $gallery_new = new ProductGallery();
                    $gallery_new->product_id = $product->id;
                    $gallery_new->image = $image_name;
                    $gallery_new->save();
                }
            }
        }

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('admin.private_product.index')->with($notification);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        $gallery = $product->gallery;
        $old_thumbnail = $product->thumb_image;
        $product->delete();
        if ($old_thumbnail) {
            if (File::exists(public_path() . '/' . $old_thumbnail)) unlink(public_path() . '/' . $old_thumbnail);
        }
        foreach ($gallery as $image) {
            $old_image = $image->image;
            $image->delete();
            if ($old_image) {
                if (File::exists(public_path() . '/' . $old_image)) unlink(public_path() . '/' . $old_image);
            }
        }
        ProductVariant::where('product_id', $id)->delete();
        ProductVariantItem::where('product_id', $id)->delete();
        CampaignProduct::where('product_id', $id)->delete();
        ProductReport::where('product_id', $id)->delete();
        ProductReview::where('product_id', $id)->delete();
        ProductSpecification::where('product_id', $id)->delete();
        Wishlist::where('product_id', $id)->delete();

        $notification = trans('admin_validation.Delete Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function changeStatus($id)
    {
        $product = Product::find($id);
        if ($product->status == 1) {
            $product->status = 0;
            $product->save();
            $message = trans('admin_validation.InActive Successfully');
        } else {
            $product->status = 1;
            $product->save();
            $message = trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }

    public function removedProductExistSpecification($id)
    {
        $productSpecification = ProductSpecification::find($id);
        $productSpecification->delete();
        $message = trans('admin_validation.Removed Successfully');
        return response()->json($message);
    }
}
