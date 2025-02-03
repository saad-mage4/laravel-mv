@extends('admin.master_layout')
@section('title')
<title>Private Product</title>
@endsection
@section('admin-content')
<?php
$countries = App\Models\Country::orderBy('name','asc')->where('status',1)->get();
$states = App\Models\CountryState::orderBy('name','asc')->where(['status' => 1, 'country_id' => 0])->get();
$cities = App\Models\City::orderBy('name','asc')->where(['status' => 1, 'country_state_id' => 0])->get();
?>
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Edit Private Product</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">Edit Private Product</div>
            </div>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.product.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> Private Product</a>
            <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.private_product.update',$product->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>{{__('admin.Thumbnail Image Preview')}}</label>
                                    <div>
                                        <img id="preview-img" class="admin-img" src="{{ asset($product->thumb_image) }}" alt="">
                                    </div>

                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Thumnail Image')}} <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control-file"  name="thumb_image" onchange="previewThumnailImage(event)">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Current Banner Image')}}</label>
                                    <div>
                                        <img id="preview-img" width="200px" src="{{ asset($product->banner_image) }}" alt="">
                                    </div>
                                </div>

                                {{-- {{dd($gallery)}} --}}
                                <div class="form-group col-12">
                                    <label>{{__('admin.Banner Image')}} <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control-file"  name="banner_image">
                                </div>

                            {{-- <div class="form-group col-12">
                                     <label for="">{{__('user.New Image (Multiple)')}}</label>
                                <input type="file" class="form-control-file" name="images[]" multiple onchange="imageGalleryPreview(event)"
                                accept="image/*">
                                </div> --}}
                                {{-- <div id="Image_Preview_Slider" style="display: none;"></div> --}}


                                {{-- <div id="Image_Preview_Slider">
                                @foreach ($gallery as $image)
                                        <img class="admin-img" src="{{ asset($image->image) }}" alt="preview-image"  loading="lazy">
                                @endforeach
                            </div> --}}




                                <div class="form-group col-12">
                                    <label>{{__('admin.Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="name" class="form-control"  name="name" value="{{ $product->name }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Slug')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="slug" class="form-control"  name="slug" value="{{ $product->slug }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Category')}} <span class="text-danger">*</span></label>
                                    <select name="category" class="form-control select2" id="category">
                                        <option value="">{{__('admin.Select Category')}}</option>
                                        @foreach ($categories as $category)
                                        @if ($category->status == 1)
                                        <option {{ $product->private_category_id == $category->id ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Sub Category')}}</label>
                                    <select name="sub_category" class="form-control select2" id="sub_category">
                                        <option value="">{{__('admin.Select Sub Category')}}</option>
                                        @if ($product->sub_category_id != 0)
                                            @foreach ($subCategories as $subCategory)
                                            <option {{ $product->private_sub_category_id == $subCategory->id ? 'selected' : '' }} value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Child Category')}}</label>
                                    <select name="child_category" class="form-control select2" id="child_category">
                                        <option value="">{{__('admin.Select Child Category')}}</option>
                                        @if ($product->child_category_id != 0)
                                            @foreach ($childCategories as $childCategory)
                                            <option {{ $product->private_child_category_id == $childCategory->id ? 'selected' : '' }} value="{{ $childCategory->id }}">{{ $childCategory->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Brand')}} <span class="text-danger">*</span></label>
                                    <select name="brand" class="form-control select2" id="brand">
                                        <option value="">{{__('admin.Select Brand')}}</option>
                                        @foreach ($brands as $brand)
                                        @if ($brand->status == 1)
                                        <option {{ $product->brand_id == $brand->id ? 'selected' : '' }} value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>

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


                                <div class="form-group col-12">
                                    <label>{{__('admin.Price')}} <span class="text-danger">*</span></label>
                                   <input type="text" class="form-control" name="price" value="{{ $product->price }}">
                                </div>


                                <div class="form-group col-12">
                                    <label>Private Phone </label>
                                   <input type="text" class="form-control" name="private_phone" value="{{$product->private_phone}}">
                                </div>


                                 <div class="form-group col-12">
                                    <label for="private_country">Private Country </label>
                                     <select class="form-control select2" name="private_country" id="country_id">
                                       <option value="">{{__('user.Select Country')}}</option>
                                        @foreach ($countries as $country)
                                                    <option  {{ $product->private_country == $country->id ? 'selected' : '' }} value="{{ $country->id }}" data-name="{{ $country->name }}">{{ $country->name }}</option>
                                                @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-12">
                                    <label for="private_state">Private State</label>
                                     <select class="form-control select2" name="private_state" id="state_id">
                                                <option value="">{{__('user.Select State')}}</option>
                                        @foreach ($states as $state)
                                                    <option {{ $producct->private_state ==$state->id ? 'selected' : '' }}  value="{{ $state->id }}" data-name="{{ $state->name }}">{{ $state->name }}</option>
                                                @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-12">
                                    <label for="private_city">Private City </label>
                                     <select class="form-control select2" name="private_city" id="city_id">
                                                <option value="">{{__('user.Select City')}}</option>
                                        @foreach ($cities as $city)
                                                    <option {{ $product->private_city == $state->id ? 'selected' : '' }} value="{{ $city->id }}" data-name="{{ $city->name }}">{{ $city->name }}</option>
                                                @endforeach
                                    </select>
                                </div>











                                <div class="form-group col-12">
                                    <label>{{__('admin.Long Description')}} <span class="text-danger">*</span></label>
                                    <textarea name="long_description" id="" cols="30" rows="10" class="summernote">{{ $product->long_description }}</textarea>
                                </div>














                                <div class="form-group col-12">
                                    <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control">
                                        <option {{ $product->status == 1 ? 'selected' : '' }} value="1">{{__('admin.Active')}}</option>
                                        <option {{ $product->status == 0 ? 'selected' : '' }} value="0">{{__('admin.Inactive')}}</option>
                                    </select>
                                </div>













                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-primary">{{__('admin.Update')}}</button>
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

            const CategoryLoad = () => {
                   var categoryId = $("#category").val();
                if(categoryId){
                    $.ajax({
                        type:"get",
                        url:"{{url('/admin/private_subcategory-by-category/')}}"+"/"+categoryId,
                        success:function(response){
                            $("#sub_category").html(response.subCategories);
                            var response= "<option value=''>{{__('admin.Select Child Category')}}</option>";
                            $("#child_category").html(response);

                        },
                        error:function(err){
                            console.log(err);

                        }
                    })
                }else{
                    var response= "<option value=''>{{__('admin.Select Sub Category')}}</option>";
                    $("#sub_category").html(response);
                    var response= "<option value=''>{{__('admin.Select Child Category')}}</option>";
                    $("#child_category").html(response);
                }
            }

            CategoryLoad();

            $("#category").on("change",function(e){
                 e.preventDefault();
             CategoryLoad();
            })

            $("#sub_category").on("change",function(){
                var SubCategoryId = $("#sub_category").val();
                if(SubCategoryId){
                    $.ajax({
                        type:"get",
                        url:"{{url('/admin/private_childcategory-by-subcategory/')}}"+"/"+SubCategoryId,
                        success:function(response){
                            $("#child_category").html(response.childCategories);
                        },
                        error:function(err){
                            console.log(err);

                        }
                    })
                }else{
                    var response= "<option value=''>{{__('admin.Select Child Category')}}</option>";
                    $("#child_category").html(response);
                }

            })


             // onLoad
            const handleCountryChange = () => {
                let countryId = $("#country_id").val();
                    let countryName = $("#country_id option:selected").data('name');
                    if(countryId){
                        $.ajax({
                            type:"get",
                            url:"{{url('/admin/state-by-country/')}}"+"/"+countryId,
                            success:function(response){
                                $("#state_id").html(response.states);
                                $("#city_id").html("<option value=''>{{__('user.Select a City')}}</option>");
                            },
                            error:function(err){
                                console.table(err);
                            }
                        })
                    }else{
                        $("#state_id").html("<option value=''>{{__('user.Select a State')}}</option>");
                        $("#city_id").html("<option value=''>{{__('user.Select a City')}}</option>");
                    }
            }

            handleCountryChange();


             //   Country Select
        $("#country_id").on("change", function (e) {
            e.preventDefault();
            handleCountryChange();
        });

            $("#state_id").on("change",function(e){
                e.preventDefault();
                let stateId = $("#state_id").val();
                const stateName = $("#state_id option:selected").data('name');
                if(stateId){
                    $.ajax({
                        type:"get",
                        url:"{{url('/admin/city-by-state/')}}"+"/"+stateId,
                        success:function(response){
                            $("#city_id").html(response.cities);
                        },
                        error:function(err){
                            console.table(err);
                        }
                    })
                }else{
                   $("#city_id").html("<option value=''>{{__('user.Select a City')}}</option>");
                }

            })


            $("#city_id").on("change", function() {
            let cityId = $("#city_id").val();
            const cityName = $("#city_id option:selected").data('name');
            });



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
      const maxFiles = 8;
    const files = e?.target?.files;

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
            img.loading = 'lazy';

            // Append the image to the preview container
            previewContainer.appendChild(img);
        }

        reader.readAsDataURL(file);
     });
}
</script>


@endsection
