@php
    $setting = App\Models\Setting::first();
    $authUser = Auth::guard('web')->user();
@endphp
@extends('seller.master_layout')
@section('title')
<title>{{__('user.Products')}}</title>
@endsection
@section('seller-content')

<style>
    div#Image_Preview_Slider {
    grid-template-columns: repeat(5,1fr);
    gap: 10px;
    margin-bottom: 15px;
}

div#Image_Preview_Slider img {
    width: 100px;
    height: 100px;
}

@media (max-width: 700px) {
    div#Image_Preview_Slider {
    grid-template-columns: repeat(4,1fr);
}
div#Image_Preview_Slider img {
    width: 70px;
    height: 70px;
}
}
</style>
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('user.Create Product')}}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('seller.dashboard') }}">{{__('user.Dashboard')}}</a></div>
              <div class="breadcrumb-item">{{__('user.Create Product')}}</div>
            </div>
          </div>

          <div class="section-body">
            <a href="{{ route('seller.product.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> {{__('user.Products')}}</a>
            <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                        <form  action="{{ route('seller.product.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>{{__('user.Thumbnail Image Preview')}}</label>
                                    <div>
                                        <img id="preview-img" class="admin-img" src="{{ asset('uploads/website-images/preview.png') }}" alt="">
                                    </div>

                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('user.Thumnail Image')}} <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control-file"  name="thumb_image" onchange="previewThumnailImage(event)">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('user.Banner Image')}} <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control-file"  name="banner_image">
                                </div>

                                {{-- @if ($authUser->seller_type == "Private") --}}
                                {{-- Image Gallery  --}}
                            <div class="form-group col-12">
                                     <label for="">{{__('user.New Image (Multiple)')}}</label>
                                <input type="file" class="form-control-file" name="images[]" multiple onchange="imageGalleryPreview(event)"
                                accept="image/*">
                                </div>
                                <div id="Image_Preview_Slider" style="display: none;"></div>
                                {{-- @endif --}}

                                {{-- @if ($authUser->seller_type == "Public")
                                <div class="form-group col-12">
                                    <label>{{__('user.Short Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="short_name" class="form-control"  name="short_name" value="{{ old('short_name') }}">
                                </div>
                                @endif --}}

                                <div class="form-group col-12">
                                    <label>{{__('user.Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="name" class="form-control"  name="name" value="{{ old('name') }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('user.Slug')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="slug" class="form-control"  name="slug" value="{{ old('slug') }}">
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
                                <div class="form-group col-12">
                                    <label>{{__('user.Category')}} <span class="text-danger">*</span></label>
                                    <select name="category" class="form-control select2" id="category">
                                        <option value="">{{__('user.Select Category')}}</option>
                                        @foreach ($categories as $category)
                                            @if ($category->slug !== 'used-products')
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                                </div>

                                @endif




                                      @if ($authUser->seller_type == "Private")
                                <div class="form-group col-12">
                                    <label>Private Category<span class="text-danger">*</span></label>
                                    <select name="private_category" class="form-control select2" id="private_category">
                                        <option value="">Select Private Category</option>
                                        @foreach ($privates_categories as $private_category)
                                            <option value="{{ $private_category->id }}">{{ $private_category->name }}</option>
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
                                            <option {{ old('brand') == $brand->id ? 'selected' : '' }} value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                @if ($authUser->seller_type == "Private")
                                <div class="form-group col-12">
                                    <label for="private_ad_type">Product Condition <span class="text-danger">*</span></label>
                                     <select name="private_ad_type" id="private_ad_type" class="form-control select2">
                                        <option value="">Product Condition</option>
                                        @foreach ($ads as $ad)
                                            <option {{ old('ad') == $ad->id ? 'selected' : '' }} value="{{ $ad->id }}">{{ $ad->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif


                                @if ($authUser->seller_type == "Public")
                                <div class="form-group col-12">
                                    <label>{{__('user.SKU')}} </label>
                                   <input type="text" class="form-control" name="sku">
                                </div>
                                @endif

                                <div class="form-group col-12">
                                    <label>{{__('user.Price')}} <span class="text-danger">*</span></label>
                                   <input type="text" class="form-control" name="price" value="{{ old('price') }}">
                                </div>

                                @if ($authUser->seller_type == "Public")
                                <div class="form-group col-12">
                                    <label>{{__('user.Offer Price')}}</label>
                                   <input type="text" class="form-control" name="offer_price" value="{{ old('offer_price') }}">
                                </div>
                                @endif


                                @if ($authUser->seller_type == "Public")
                                <div class="form-group col-12">
                                    <label>{{__('user.Stock Quantity')}} <span class="text-danger">*</span></label>
                                   <input type="number" class="form-control" name="quantity" value="{{ old('quantity') }}">
                                </div>
                                @endif

                                  {{-- @if ($authUser->seller_type == "Public")
                                <div class="form-group col-12">
                                    <label>{{__('user.Video Link')}}</label>
                                   <input type="text" class="form-control" name="video_link" value="{{ old('video_link') }}">
                                </div>
                                @endif --}}

                                {{-- @if ($authUser->seller_type == "Public")
                                <div class="form-group col-12">
                                    <label>{{__('user.Short Description')}} <span class="text-danger">*</span></label>
                                    <textarea name="short_description" id="" cols="30" rows="10" class="form-control text-area-5">{{ old('short_description') }}</textarea>
                                </div>
                                @endif --}}

                                <div class="form-group col-12">
                                    <label>
                                     {{ $authUser->seller_type == "Private" ? "Description" : __('user.Long Description') }}
                                        <span class="text-danger">*</span></label>
                                    <textarea name="long_description" id="" cols="30" rows="10" class="summernote">{{ old('long_description') }}</textarea>
                                </div>
                                {{-- @if ($authUser->seller_type == "Public")
                                <div class="form-group col-12">
                                    <label>{{__('user.Tags')}}</label>
                                   <input type="text" class="form-control tags" name="tags" value="{{ old('tags') }}">
                                </div>
                                @endif --}}
                                @if ($authUser->seller_type == "Public")
                                <div class="form-group col-12">
                                    <label>{{__('user.Tax')}} <span class="text-danger">*</span></label>
                                    <select name="tax" class="form-control">
                                        <option value="">{{__('user.Select Tax')}}</option>
                                        @foreach ($productTaxs as $tax)
                                            <option {{ old('tax') == $tax->id ? 'selected' : '' }}  value="{{ $tax->id }}">{{ $tax->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                @if ($authUser->seller_type == "Public")
                                    <div class="form-group col-12">
                                        <label>{{__('user.Product Return Availabe ?')}} <span class="text-danger">*</span></label>
                                        <select name="is_return" class="form-control" id="is_return" >
                                            <option value="0">{{__('user.No')}}</option>
                                            <option value="1">{{__('user.Yes')}}</option>
                                        </select>
                                    </div>
                                @endif
                                  @if ($authUser->seller_type == "Public")
                                <div class="form-group col-12 d-none" id="policy_box">
                                    <label>{{__('user.Return Policy')}} <span class="text-danger">*</span></label>
                                    <select name="return_policy_id" class="form-control">
                                        @foreach ($retrunPolicies as $retrunPolicy)
                                            <option value="{{ $retrunPolicy->id }}">{{ $retrunPolicy->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                  @if ($authUser->seller_type == "Public")
                                <div class="form-group col-12">
                                    <label>{{__('user.Warranty Available ?')}}  <span class="text-danger">*</span></label>
                                    <select name="is_warranty" class="form-control">
                                        <option value="1">{{__('user.Yes')}}</option>
                                        <option value="0">{{__('user.No')}}</option>
                                    </select>
                                </div>
                                @endif
                                  {{-- @if ($authUser->seller_type == "Public")
                                <div class="form-group col-12">
                                    <label>{{__('user.SEO Title')}}</label>
                                   <input type="text" class="form-control" name="seo_title" value="{{ old('seo_title') }}">
                                </div>
                                @endif
                                  @if ($authUser->seller_type == "Public")
                                <div class="form-group col-12">
                                    <label>{{__('user.SEO Description')}}</label>
                                    <textarea name="seo_description" id="" cols="30" rows="10" class="form-control text-area-5">{{ old('seo_description') }}</textarea>
                                </div>
                                @endif --}}


                                {{-- @if ($authUser->seller_type == "Public")
                                <div class="form-group col-12">
                                    <label>{{__('user.Specifications')}}</label>
                                    <div>
                                        <a href="javascript::void()" id="manageSpecificationBox">
                                            <input name="is_specification" id="status_toggle" type="checkbox" checked data-toggle="toggle" data-on="Enable" data-off="Disabled" data-onstyle="success" data-offstyle="danger">
                                        </a>
                                    </div>
                                </div>
                                @endif --}}

                                {{-- @if ($authUser->seller_type == "Public")
                                <div class="form-group col-12" id="specification-box">
                                    <div class="row">
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

                                @if ($authUser->seller_type == "Public")
                                <div id="hidden-specification-box" class="d-none">
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
                                </div>
                                @endIf
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-primary">{{__('user.Save')}}</button>
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
        var specification = true;
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

            //! For Private Sub & Child Category

             $("#private_category").on("change",function(){
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


            })

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

            //! Private Sub Category End

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

const imageGalleryPreview = (e) => {
      const maxFiles = 5;
    const files = e.target.files;

    if (files.length > maxFiles) {
        alert(`You can only upload up to ${maxFiles} images.`);
        e.target.value = ''; // Reset the file input
        return;
    }
    const previewContainer = document.getElementById('Image_Preview_Slider');
    previewContainer.style.display = "grid";

    // Clear any existing images in the preview container
    previewContainer.innerHTML = '';

    Array.from(e.target.files)?.forEach((file) => {
        const reader = new FileReader();

        reader.onload = function() {
            // Create an image element
            const img = document.createElement('img');
            img.className = 'admin-img';
            img.src = reader.result;
            img.alt = 'Preview Image';

            // Append the image to the preview container
            previewContainer.appendChild(img);
        }

        reader.readAsDataURL(file);
    });
}



</script>




@endsection
