@php
    $setting = App\Models\Setting::first();
    $authUser = Auth::guard('web')->user();
@endphp
@extends('seller.master_layout')
@section('title')
<title>{{__('user.Products')}}</title>
@endsection
@section('seller-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('user.Edit Product')}}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('seller.dashboard') }}">{{__('user.Dashboard')}}</a></div>
              <div class="breadcrumb-item">{{__('user.Edit Product')}}</div>
            </div>
          </div>

          <div class="section-body">
            <a href="{{ route('seller.product.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> {{__('user.Products')}}</a>
            <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('seller.product.update',$product->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>{{__('user.Thumbnail Image Preview')}}</label>
                                    <div>
                                        <img id="preview-img" class="admin-img" src="{{ asset($product->thumb_image) }}" alt="">
                                    </div>

                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('user.Thumnail Image')}} <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control-file"  name="thumb_image" onchange="previewThumnailImage(event)">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('user.Current Banner Image')}}</label>
                                    <div>
                                        <img id="preview-img" width="200px" src="{{ asset($product->banner_image) }}" alt="">
                                    </div>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('user.Banner Image')}} <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control-file"  name="banner_image">
                                </div>

                                {{-- @if ($authUser->seller_type == "Public")
                                <div class="form-group col-12">
                                    <label>{{__('user.Short Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="short_name" class="form-control"  name="short_name" value="{{ $product->short_name }}">
                                </div>
                                @endif --}}

                                <div class="form-group col-12">
                                    <label>{{__('user.Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="name" class="form-control"  name="name" value="{{ $product->name }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('user.Slug')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="slug" class="form-control"  name="slug" value="{{ $product->slug }}">
                                </div>
                                     {{-- Category of in Private  --}}
                                @if ($authUser->seller_type == "Private")
                            @foreach ($categories as $item)
                    @if (isset($item->slug) && $item->slug === 'used-products')
                            <div class="form-group col-12 d-none">
                                    <label>{{__('user.Category')}} <span class="text-danger">*</span></label>
                                    <input name="category"  class="form-control"  readonly type="text" value="{{ $item['name']}}" />
                                </div>
                                @endif
                @endforeach
                                @else
                                {{-- <div class="form-group col-12">
                                    <label>{{__('user.Category')}} <span class="text-danger">*</span></label>
                                    <select name="category" class="form-control select2" id="category">
                                        <option value="">{{__('user.Select Category')}}</option>
                                        @foreach ($categories as $category)
                                            @if ($category->slug !== 'used-products')
                                             <option {{ $product->category_id == $category->id ? 'selected' : '' }}
                                                value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>


                                <div class="form-group col-12">
                                    <label>{{__('user.Sub Category')}}</label>
                                    <select name="sub_category" class="form-control select2" id="sub_category">
                                        <option value="">{{__('user.Select Sub Category')}}</option>
                                    </select>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('user.Child Category')}}</label>
                                    <select name="child_category" class="form-control select2" id="child_category">
                                        <option value="">{{__('user.Select Child Category')}}</option>
                                    </select>
                                </div> --}}

                                <div class="form-group col-12">
                                    <label>{{__('user.Category')}} <span class="text-danger">*</span></label>
                                    <select name="category" class="form-control select2" id="category">
                                        <option value="">{{__('user.Select Category')}}</option>
                                        @foreach ($categories as $category)
                                        @if ($category->status == 1)
                                        <option {{ $product->category_id == $category->id ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>


                                <div class="form-group col-12">
                                    <label>{{__('user.Sub Category')}}</label>
                                    <select name="sub_category" class="form-control select2" id="sub_category">
                                        <option value="">{{__('user.Select Sub Category')}}</option>
                                        @if ($product->sub_category_id != 0)
                                            @foreach ($subCategories as $subCategory)
                                            <option {{ $product->sub_category_id == $subCategory->id ? 'selected' : '' }} value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('user.Child Category')}}</label>
                                    <select name="child_category" class="form-control select2" id="child_category">
                                        <option value="">{{__('user.Select Child Category')}}</option>
                                        @if ($product->child_category_id != 0)
                                            @foreach ($childCategories as $childCategory)
                                            <option {{ $product->child_category_id == $childCategory->id ? 'selected' : '' }} value="{{ $childCategory->id }}">{{ $childCategory->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                @endif


                                     @if ($authUser->seller_type == "Private")
                                <div class="form-group col-12">
                                    <label>Private Category<span class="text-danger">*</span></label>
                                    <select name="private_category" class="form-control select2" id="private_category">
                                        <option value="">Select Private Category</option>
                                        @foreach ($privates_categories as $private_category)
                                        @if ($private_category->status == 1)
                                        <option {{ $product->private_category_id == $private_category->id ? 'selected' : '' }} value="{{ $private_category->id }}">{{ $private_category->name }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-12">
                                    <label>Private Sub Category</label>
                                    <select name="private_sub_category" class="form-control select2" id="private_sub_category">
                                        <option value="">{{__('user.Select Sub Category')}}</option>
                                    </select>
                                </div>

                                <div class="form-group col-12">
                                    <label>Private Child Category</label>
                                    <select name="private_child_category" class="form-control select2" id="private_child_category">
                                        <option value="">{{__('user.Select Child Category')}}</option>
                                    </select>
                                </div>
                                @endIf


                                <div class="form-group col-12">
                                    <label>{{__('user.Brand')}} <span class="text-danger">*</span></label>
                                    <select name="brand" class="form-control select2" id="brand">
                                        <option value="">{{__('user.Select Brand')}}</option>
                                        @foreach ($brands as $brand)
                                        @if ($brand->status == 1)
                                        <option {{ $product->brand_id == $brand->id ? 'selected' : '' }} value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>

                                @if ($authUser->seller_type == "Private")
                                <div class="form-group col-12">
                                    <label for="private_ad_type">Ad Type <span class="text-danger">*</span></label>
                                     <select name="private_ad_type" id="private_ad_type" class="form-control select2">
                                        <option value="">Ad Type</option>
                                        @foreach ($ads as $ad)
                                        @if ($ad->status == 1)
                                        <option {{ $product->private_ad_type == $ad->id ? 'selected' : '' }} value="{{ $ad->id }}">{{ $ad->name }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                                @if ($authUser->seller_type == "Public")
                                <div class="form-group col-12">
                                    <label>{{__('user.SKU')}} </label>
                                   <input type="text" class="form-control" name="sku" value="{{ $product->sku }}">
                                </div>
                                @endIf

                                <div class="form-group col-12">
                                    <label>{{__('user.Price')}} <span class="text-danger">*</span></label>
                                   <input type="text" class="form-control" name="price" value="{{ $product->price }}">
                                </div>
                                 @if ($authUser->seller_type == "Public")
                                <div class="form-group col-12">
                                    <label>{{__('user.Offer Price')}}</label>
                                   <input type="text" class="form-control" name="offer_price" value="{{ $product->offer_price }}">
                                </div>
                                @endIf


                                    @if ($authUser->seller_type == "Public")
                                                                    <div class="form-group col-12">
                                                                        <label>{{__('user.Stock Quantity')}} <span class="text-danger">*</span></label>
                                                                    <input type="number" class="form-control" name="quantity" value="{{ $product->qty }}">
                                                                    </div>

                                                                    {{-- @if ($product->video_link)
                                                                        <div class="form-group col-12">
                                                                            <label>{{__('user.Video Preview')}}</label>
                                                                            @php
                                                                                $video_id=explode("=",$product->video_link);
                                                                            @endphp
                                                                            <div>
                                                                                <iframe width="300" height="200"
                                                                                    src="https://www.youtube.com/embed/{{ $video_id[1] }}">
                                                                                </iframe>
                                                                            </div>

                                                                        </div>
                                                                    @endif

                                                                    <div class="form-group col-12">
                                                                        <label>{{__('user.Video Link')}}</label>
                                                                    <input type="text" class="form-control" name="video_link" value="{{ $product->video_link }}">
                                                                    </div> --}}
                                    @endIf

                                {{-- @if ($authUser->seller_type == "Public")
                                <div class="form-group col-12">
                                    <label>{{__('user.Short Description') }} <span class="text-danger">*</span></label>
                                    <textarea name="short_description" id="" cols="30" rows="10" class="form-control text-area-5">{{ $product->short_description }}</textarea>
                                </div>
                                @endIf --}}

                                <div class="form-group col-12">
                                    <label>{{__('user.Long Description')}} <span class="text-danger">*</span></label>
                                    <textarea name="long_description" id="" cols="30" rows="10" class="summernote">{{ $product->long_description }}</textarea>
                                </div>



@if ($authUser->seller_type == "Public")
                                {{-- <div class="form-group col-12">
                                    <label>{{__('user.Tags')}}</label>
                                   <input type="text" class="form-control tags" name="tags" value="{{ $tags }}">
                                </div> --}}

                                <div class="form-group col-12">
                                    <label>{{__('user.Tax')}} <span class="text-danger">*</span></label>
                                    <select name="tax" class="form-control">
                                        <option value="">{{__('user.Select Tax')}}</option>
                                        @foreach ($productTaxs as $tax)
                                        @if ($tax->status == 1)
                                        <option {{ $product->tax_id == $tax->id ? 'selected' : '' }}  value="{{ $tax->id }}">{{ $tax->title }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('user.Product Return Availabe ?')}} <span class="text-danger">*</span></label>
                                    <select name="is_return" class="form-control" id="is_return" >
                                        <option {{ $product->is_return == 0 ? 'selected' : '' }} value="0">{{__('user.No')}}</option>
                                        <option {{ $product->is_return == 1 ? 'selected' : '' }} value="1">{{__('user.Yes')}}</option>
                                    </select>
                                </div>


                                @if ($product->is_return == 1)
                                    <div class="form-group col-12" id="policy_box">
                                        <label>{{__('user.Return Policy')}} <span class="text-danger">*</span></label>
                                        <select name="return_policy_id" class="form-control">
                                            @foreach ($retrunPolicies as $retrunPolicy)
                                                <option {{ $product->return_policy_id == $retrunPolicy->id ? 'selected' : '' }} value="{{ $retrunPolicy->id }}">{{ $retrunPolicy->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                @if ($product->is_return != 1)
                                    <div class="form-group col-12 d-none" id="policy_box">
                                        <label>{{__('user.Return Policy')}} <span class="text-danger">*</span></label>
                                        <select name="return_policy_id" class="form-control">
                                            @foreach ($retrunPolicies as $retrunPolicy)
                                                <option value="{{ $retrunPolicy->id }}">{{ $retrunPolicy->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <div class="form-group col-12">
                                    <label>{{__('user.Warranty Available ?')}}  <span class="text-danger">*</span></label>
                                    <select name="is_warranty" class="form-control">
                                        <option {{ $product->is_warranty == 1 ? 'selected' : '' }} value="1">{{__('user.Yes')}}</option>
                                        <option {{ $product->is_warranty == 0 ? 'selected' : '' }} value="0">{{__('user.No')}}</option>
                                    </select>
                                </div>

                                {{-- <div class="form-group col-12">
                                    <label>{{__('user.SEO Title')}}</label>
                                   <input type="text" class="form-control" name="seo_title" value="{{ $product->seo_title }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('user.SEO Description')}}</label>
                                    <textarea name="seo_description" id="" cols="30" rows="10" class="form-control text-area-5">{{ $product->seo_description }}</textarea>
                                </div> --}}

                                {{-- <div class="form-group col-12">
                                    <label>{{__('user.Specifications')}}</label>
                                    <div>
                                        @if ($product->is_specification==1)
                                            <a href="javascript::void()" id="manageSpecificationBox">
                                                <input name="is_specification" id="status_toggle" type="checkbox" checked data-toggle="toggle" data-on="{{__('user.Enable')}}" data-off="{{__('user.Disable')}}" data-onstyle="success" data-offstyle="danger">
                                            </a>
                                        @else
                                        <a href="javascript::void()" id="manageSpecificationBox">
                                                <input name="is_specification" id="status_toggle" type="checkbox" data-toggle="toggle" data-on="{{__('user.Enable')}}" data-off="{{__('user.Disable')}}" data-onstyle="success" data-offstyle="danger">
                                            </a>
                                        @endif

                                    </div>
                                </div>
                                @if ($product->is_specification==1)
                                    <div class="form-group col-12" id="specification-box">
                                        @if ($productSpecifications->count() != 0)
                                            @foreach ($productSpecifications as $productSpecification)
                                                <div class="row mt-2" id="existSpecificationBox-{{ $productSpecification->id }}">
                                                    <div class="col-md-5">
                                                        <label>{{__('user.Key')}} <span class="text-danger">*</span></label>
                                                        <select name="keys[]" class="form-control">
                                                            @foreach ($specificationKeys as $specificationKey)
                                                                <option {{ $specificationKey->id == $productSpecification->product_specification_key_id ? 'selected' : '' }} value="{{ $specificationKey->id }}">{{ $specificationKey->key }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label>{{__('user.Specification')}} <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="specifications[]" value="{{ $productSpecification->specification }}">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-danger plus_btn removeExistSpecificationRow"  data-specificationiId="{{ $productSpecification->id }}"><i class="fas fa-trash"></i></button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif

                                        <div class="row mt-2">
                                            <div class="col-md-5">
                                                <label>{{__('user.Key')}} <span class="text-danger">*</span></label>
                                                <select name="keys[]" class="form-control">
                                                    @foreach ($specificationKeys as $specificationKey)
                                                        <option value="{{ $specificationKey->id }}">{{ $specificationKey->key }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <label>{{__('user.Specification')}} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="specifications[]">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-success plus_btn" id="addNewSpecificationRow"><i class="fas fa-plus"></i></button>
                                            </div>
                                        </div>

                                    </div>
                                @endif --}}

                                {{-- @if ($product->is_specification==0)
                                    <div class="form-group col-12 d-none" id="specification-box">
                                        @if ($productSpecifications->count() != 0)
                                            @foreach ($productSpecifications as $productSpecification)
                                                <div class="row mt-2" id="existSpecificationBox-{{ $productSpecification->id }}">
                                                    <div class="col-md-5">
                                                        <label>{{__('user.Key')}} <span class="text-danger">*</span></label>
                                                        <select name="keys[]" class="form-control">
                                                            @foreach ($specificationKeys as $specificationKey)
                                                                <option {{ $specificationKey->id == $productSpecification->product_specification_key_id ? 'selected' : '' }} value="{{ $specificationKey->id }}">{{ $specificationKey->key }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label>{{__('user.Specification')}} <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="specifications[]" value="{{ $productSpecification->specification }}">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-danger plus_btn removeExistSpecificationRow"  data-specificationiId="{{ $productSpecification->id }}"><i class="fas fa-trash"></i></button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif

                                        <div class="row mt-2">
                                            <div class="col-md-5">
                                                <label>{{__('user.Key')}} <span class="text-danger">*</span></label>
                                                <select name="keys[]" class="form-control">
                                                    @foreach ($specificationKeys as $specificationKey)
                                                        <option value="{{ $specificationKey->id }}">{{ $specificationKey->key }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <label>{{__('user.Specification')}} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="specifications[]">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-success plus_btn" id="addNewSpecificationRow"><i class="fas fa-plus"></i></button>
                                            </div>
                                        </div>

                                    </div>
                                @endif --}}




                                {{-- <div id="hidden-specification-box" class="d-none">
                                    <div class="delete-specification-row">
                                        <div class="row mt-2">
                                            <div class="col-md-5">
                                                <label>{{__('user.Key')}} <span class="text-danger">*</span></label>
                                                <select name="keys[]" class="form-control">
                                                    @foreach ($specificationKeys as $specificationKey)
                                                        <option value="{{ $specificationKey->id }}">{{ $specificationKey->key }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <label>{{__('user.Specification')}} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="specifications[]">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger plus_btn deleteSpeceficationBtn"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}

@endif
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-primary">{{__('user.Update')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                  </div>
                </div>
          </div>
        </section>
      </div>


<script>
    (function($) {
        "use strict";
        var specification = '{{ $product->is_specification == 1 ? true : false }}';
        $(document).ready(function () {
            $("#name").on("focusout",function(e){
                $("#slug").val(convertToSlug($(this).val()));
            })


            $("#category").on("change",function(){
                 const categoryId = $("#category").val();
                if(categoryId){
                    $.ajax({
                        type:"get",
                        url:"{{url('/seller/subcategory-by-category/')}}"+"/"+categoryId,
                        success:function(response){
                            $("#sub_category").html(response.subCategories);
                            var response= "<option value=''>{{__('user.Select Child Category')}}</option>";
                            $("#child_category").html(response);

                        },
                        error:function(err){
                            console.log(err);

                        }
                    })
                }else{
                    var response= "<option value=''>{{__('user.Select Sub Category')}}</option>";
                    $("#sub_category").html(response);
                    var response= "<option value=''>{{__('user.Select Child Category')}}</option>";
                    $("#child_category").html(response);
                }
            })

            $("#sub_category").on("change",function(){
                var SubCategoryId = $("#sub_category").val();
                if(SubCategoryId){
                    $.ajax({
                        type:"get",
                        url:"{{url('/seller/childcategory-by-subcategory/')}}"+"/"+SubCategoryId,
                        success:function(response){
                            $("#child_category").html(response.childCategories);
                        },
                        error:function(err){
                            console.log(err);

                        }
                    })
                }else{
                    var response= "<option value=''>{{__('user.Select Child Category')}}</option>";
                    $("#child_category").html(response);
                }

            })


            // For Private Sub & Child Category
        function loadSubCategoriesAndChildCategories() {
         var categoryId = $("#private_category").val();
                if(categoryId){
                    $.ajax({
                        type:"get",
                        url:"{{url('/seller/private_subcategory-by-category/')}}"+"/"+categoryId,
                        success:function(response){
                            $("#private_sub_category").html(response.subCategories);
                            var response= "<option value=''>{{__('user.Select Child Category')}}</option>";
                            $("#private_child_category").html(response);
                        },
                        error:function(err){
                            console.log(err);

                        }
                    })
                }else{
                    var response= "<option value=''>{{__('user.Select Sub Category')}}</option>";
                    $("#private_sub_category").html(response);
                    var response= "<option value=''>{{__('user.Select Child Category')}}</option>";
                    $("#private_child_category").html(response);
                }
         }

             loadSubCategoriesAndChildCategories();

                // Bind the function to the change event as well
                $("#private_category").on("change", function() {
                    loadSubCategoriesAndChildCategories();
                });

            //  $("#private_category").on("change",function(){
            //     var categoryId = $("#private_category").val();
            //     if(categoryId){
            //         $.ajax({
            //             type:"get",
            //             url:"{{url('/seller/private_subcategory-by-category/')}}"+"/"+categoryId,
            //             success:function(response){
            //                 $("#private_sub_category").html(response.subCategories);
            //                 var response= "<option value=''>{{__('user.Select Child Category')}}</option>";
            //                 $("#private_child_category").html(response);
            //             },
            //             error:function(err){
            //                 console.log(err);

            //             }
            //         })
            //     }else{
            //         var response= "<option value=''>{{__('user.Select Sub Category')}}</option>";
            //         $("#private_sub_category").html(response);
            //         var response= "<option value=''>{{__('user.Select Child Category')}}</option>";
            //         $("#private_child_category").html(response);
            //     }


            // })

            $("#private_sub_category").on("change",function(){
                var SubCategoryId = $("#private_sub_category").val();
                if(SubCategoryId){
                    $.ajax({
                        type:"get",
                        url:"{{url('/seller/private_childcategory-by-subcategory/')}}"+"/"+SubCategoryId,
                        success:function(response){
                            $("#private_child_category").html(response.childCategories);
                        },
                        error:function(err){
                            console.log(err);

                        }
                    })
                }else{
                    var response= "<option value=''>{{__('user.Select Child Category')}}</option>";
                    $("#private_child_category").html(response);
                }

            })

            // Private Sub Category End

            $("#is_return").on('change',function(){
                var returnId = $("#is_return").val();
                if(returnId == 1){
                    $("#policy_box").removeClass('d-none');
                }else{
                    $("#policy_box").addClass('d-none');
                }

            })

            $("#addNewSpecificationRow").on('click',function(){
                var html = $("#hidden-specification-box").html();
                $("#specification-box").append(html);
            })

            $(document).on('click', '.deleteSpeceficationBtn', function () {
                $(this).closest('.delete-specification-row').remove();
            });


            $("#manageSpecificationBox").on("click",function(){
                if(specification){
                    specification = false;
                    $("#specification-box").addClass('d-none');
                }else{
                    specification = true;
                    $("#specification-box").removeClass('d-none');
                }
            })

            $(".removeExistSpecificationRow").on("click",function(){
                var isDemo = "{{ env('APP_VERSION') }}"
                if(isDemo == 0){
                    toastr.error('This Is Demo Version. You Can Not Change Anything');
                    return;
                }

                var specificationId = $(this).attr("data-specificationiId");
                $.ajax({
                    type:"put",
                    data: { _token : '{{ csrf_token() }}' },
                    url:"{{url('/seller/removed-product-exist-specification/')}}"+"/"+specificationId,
                    success:function(response){
                        toastr.success(response)
                        $("#existSpecificationBox-"+specificationId).remove();
                    },
                    error:function(err){
                        console.log(err);

                    }
                })
            })

        });
    })(jQuery);

    function convertToSlug(Text){
            return Text
                .toLowerCase()
                .replace(/[^\w ]+/g,'')
                .replace(/ +/g,'-');
    }

    function previewThumnailImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('preview-img');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    };

</script>


@endsection
