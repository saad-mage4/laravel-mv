@extends('layout')
@section('title')
    <title>{{ $seoSetting->seo_title }}</title>
@endsection
@section('meta')
    <meta name="description" content="{{ $seoSetting->seo_description }}">
@endsection

@section('public-content')

<style>
    /* Animate.css - Simple animation styles */

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
.animated-container {
    padding: 25px 10px;
}
@media (min-width:768px) {
    .animated-container {
    border: 2px solid #000;
    border-radius: 50px;
}
}

.animated-container  .search {
    /* width: 45%;
    display: flex;
    gap: 10px; */
    position: relative;
}
.animated-container .search  .form-control,
.animated-container .input_Search  .form-control {
display: block;
    width: 100%;
    padding: 0px;
    font-size: 1.2rem;
    appearance: auto;
    font-weight: bold;
    line-height: 1.5;
    color: #000;
    background-color: transparent !important;
    border: none !important;
    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
}


.animated-container .search .form-control:focus,
.animated-container .input_Search  .form-control:focus
{
    box-shadow: none;
}

.search::after {
    content: "";
    font-size: 20px;
    font-weight: bold;
    position: absolute;
    top: 50%;
    right: 0;
    height: 150%;
    width: 2px;
    border-right: 3px dashed #000;
    transform: translateY(-50%);
}
/* select .form-control .animated-element::after {
    content: "|";
    font-size: 20px;
    font-weight: bold;
} */

.animated-element {
  animation: fadeIn 1s ease-in-out;
  animation-fill-mode: both;
}

/* Add delay to stagger animations */
.animated-element:nth-child(1) {
  animation-delay: 0.2s;
}
.animated-element:nth-child(2) {
  animation-delay: 0.4s;
}
.animated-element:nth-child(3) {
  animation-delay: 0.6s;
}
.animated-element:nth-child(4) {
  animation-delay: 0.8s;
}


.input_Search {
    position: relative;
}

 .input_Search .X__icon {
    position: absolute;
    right: 60px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 20px;
    font-weight: bold;
    color: #000;
    display: none;
}

@media (min-width:768px) {
    .input_Search button {
    line-height: 0;
    background: none;
    border: none;
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    outline: none;
    color: #333;
    font-size: 25px;
}
}



.input-group-append {
  display: flex;
  align-items: center;
}

   @media  (max-width: 599px) {

    .search::after {
    content: unset;
}
input#searchInput {
    border: 1px solid !important;
    padding: 10px;
    margin-block: 10px;
}
#searchButton {
    width: 100%;
}
#searchButton.btn-orange {
    background-color: #ff5200;
}
#searchButton.btn-orange i {
    display: none;
}

.input_Search .X__icon {
    right: 30px;
    top: 30%;
}

   }

   /* Mobile Search Position sticky  */
.animated-container.fixed {
    position: fixed;
    top: 0px;
    z-index: 1234;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
.animated-container {
    position: relative; /* Ensure the header is positioned relative to its normal flow */
    top: -50px; /* Start the header above the viewable area */
    transition: top 1s ease-in-out; /* Animate the top property */
}
</style>

<style>
/* mobile  */

div#accordionCategories .accordion-button {
    background: transparent !important;
    padding: 0px !important;
    margin: 0px !important;
}
.desktop-menu  .wsus_menu_cat_item {
list-style: none;
padding: 0;
margin: 0;
min-height: 50px;
}

.desktop-menu  .wsus_menu_cat_item > .category-item {
position: relative;
}

.desktop-menu  .wsus_menu_cat_item > .category-item > a {
display: block;
text-decoration: none;
color: #353535;
}

/* Initially hide dropdown menus */
.desktop-menu  .wsus_menu_cat_droapdown {
 display: unset;
position: absolute;
left: 0;
top:  0;
background: #fff;
list-style: none;
padding: 0;
margin: 0;
z-index: 9999;
visibility: hidden;
opacity: 0;
min-height: 300px;
transition: none
}

#wsus__product_page  .wsus__product_sidebar{
    overflow: visible;
}

#wsus__product_page  .wsus__product_sidebar ul li a{
    transition: none;
}

.desktop-menu  .category-item.active_cat-menu .wsus_menu_cat_droapdown{
    visibility: visible;
    opacity: 1;
}

.sub-category-item {
position: relative;
}

/* Initially hide child category dropdowns */
.child-category-dropdown {
display: none;
position: absolute;
left: 100%;
top: 0;
background: #fff;
border-bottom-left-radius: 5px;
border-bottom-right-radius: 5px;
border: 1px solid #ddd;
list-style: none;
padding: 0;
margin: 0;
}

/* Show child categories on hover */
.sub-category-item:hover .child-category-dropdown {
display: block;
}

.wsus_menu_cat_droapdown li,
.child-category-dropdown li {
padding: 10px;
}

.wsus_menu_cat_droapdown li a,
.child-category-dropdown li a {
text-decoration: none;
color: #000;
display: block;
}

</style>



    <!--============================
         BREADCRUMB START
    ==============================-->
    <section id="wsus__breadcrumb" style="background: url({{  asset($banner->image) }});">
        <div class="wsus_breadcrumb_overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h4>{{__('user.Shop')}}</h4>
                        <ul>
                            <li><a href="{{ route('home') }}">{{__('user.home')}}</a></li>
                            <li><a href="{{ route('product') }}">{{__('user.shop')}}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        BREADCRUMB END
    ==============================-->

<?php
$countries = App\Models\Country::orderBy('name','asc')->where('status',1)->get();
$states = App\Models\CountryState::orderBy('name','asc')->where(['status' => 1, 'country_id' => 0])->get();
$cities = App\Models\City::orderBy('name','asc')->where(['status' => 1, 'country_state_id' => 0])->get();
?>

    <!--============================
        PRODUCT USED PAGE START
    ==============================-->
    <section id="wsus__product_page">
        <div class="container">
            <div class="row">
                {{-- Search  --}}
        <div class="col-md-6  my-3 offset-md-3">
        <div class="animated-container row">
         <div class="col-6 col-md-3 search">
               {{-- <select name="country" id="country_id" class="form-control animated-element">
           <option value="">Country</option>
            @foreach ($countries as $country)
                <option value="{{ $country->id }}" data-name="{{ $country->name }}">{{ $country->name }}</option>
            @endforeach
          </select> --}}
          <select name="state" id="state_id" class="form-control animated-element">
            <option value="">County</option>
            @foreach ($states as $state)
            <option value="{{ $state->id }}" data-name="{{ $state->name }}">{{ $state->name }}</option>
            @endforeach
          </select>
         </div>
         <div class="col-6 col-md-3 search">
            <select name="city" id="city_id" class="form-control animated-element">
            <option value="">City</option>
            @foreach ($cities as $city)
                <option value="{{ $city->id }}" data-name="{{ $city->name }}">{{ $city->name }}</option>
            @endforeach
          </select>
         </div>
          <div class="col-12 col-md-6 input_Search">
               <input type="text" id="searchInput" class="form-control animated-element" placeholder="Search By Anything..">
               <span id="resetInput" class="X__icon">x</span>
               <button type="submit" id="searchButton" class="btn btn-orange">
                <span class="text d-inline-block text-white d-md-none">Search</span>
                <i class="far fa-search" aria-hidden="true"></i></button>
          </div>
          {{-- <div class="input-group-append">
            <i class="fa-solid fa-magnifying-glass"></i>
            <button id="searchButton" class="btn btn-primary animated-element" type="button">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
          </div> --}}

        </div>
      </div>
                @if ($shop_page->status == 1)
                    <div class="col-xl-12">
                        <div class="wsus__pro_page_bammer">
                            <img src="{{ asset($shop_page->banner) }}" alt="banner" class="img-fluid w-100">
                            <div class="wsus__pro_page_bammer_text">
                                <div class="wsus__pro_page_bammer_text_center">
                                    <p>{{ $shop_page->header_one }} <span>{{ $shop_page->header_two }}</span></p>
                                    <h5>{{ $shop_page->title_one }}</h5>
                                    <h3>{{ $shop_page->title_two }}</h3>
                                    <a href="{{ $shop_page->link }}" class="add_cart">{{ $shop_page->button_text }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="col-xl-3 col-lg-4">
                    <div class="wsus__sidebar_filter ">
                        <p>{{__('user.filter')}}</p>
                        <span class="wsus__filter_icon">
                            <i class="far fa-minus" id="minus"></i>
                            <i class="far fa-plus" id="plus"></i>
                        </span>
                    </div>
                    <form id="searchProductFormId">
                    <div class="wsus__product_sidebar taha" id="sticky_sidebar">
                        <div class="accordion" id="accordionExample">
                            {{-- <div class="accordion-item">
                              <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    {{__('user.Filter By Categories')}}
                                </button>
                              </h2>
                              <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <ul>
                                        @foreach ($productCategories as $productCategory)
                                         @if ($productCategory->slug === "used-products")
                                         <li><a class="categoryForSearch" href="javascript:;" data-category="{{ $productCategory->slug }}">{{ $productCategory->name ? "New/Used" : $productCategory->name  }}</a></li>
                                         @endif
                                         @endforeach
                                        <input type="hidden" name="category" value="" id="category_id_for_search">
                                        <input type="hidden" name="page_view" value="grid_view" id="page_view_id">
                                    </ul>
                                </div>
                              </div>
                            </div> --}}

                          {{-- Private Categories  --}}

            {{-- Mobile View  --}}
            <div class="accordion-item d-lg-none d-block">
            <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
            {{__('user.Filter By Categories')}}
            </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingCategories" data-bs-parent="#accordionExample">
            <div class="accordion-body">
            <ul class="list-unstyled">
            <div class="accordion" id="accordionCategories">
                @foreach ($productPrivateCategories as $category)
            <div class="accordion-item">
            <h2 class="accordion-header" id="headingCategory{{ $loop->index }}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCategory{{ $loop->index }}" aria-expanded="false" aria-controls="collapseCategory{{ $loop->index }}">
            <li class="list-group-item border-0 ps-0">
            <a class="categoryForSearch d-block py-1" href="javascript:;" data-private_category="{{ $category->slug }}">{{ $category->name }}</a>
            </li>
            </button>

            </h2>
            <div id="collapseCategory{{ $loop->index }}" class="accordion-collapse collapse" aria-labelledby="headingCategory{{ $loop->index }}" data-bs-parent="#accordionCategories">
            <div class="accordion-body ps-4">
            @if ($category->private_subCategories->count() > 0)
            <!-- Subcategories Accordion -->
            <div class="accordion" id="accordionSubcategories{{ $loop->index }}">
            @foreach ($category->private_subCategories as $subCategory)
            <div class="accordion-item">
                @if ($subCategory->status == 1)
                <h2 class="accordion-header" id="headingSubcategory{{ $loop->parent->index }}{{ $loop->index }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSubcategory{{ $loop->parent->index }}{{ $loop->index }}" aria-expanded="false" aria-controls="collapseSubcategory{{ $loop->parent->index }}{{ $loop->index }}">
                <li class="list-group-item border-0 ps-0">
                <a class="categoryForSearch d-block py-1" href="javascript:;" data-private_sub_category="{{ $subCategory->slug }}">{{ $subCategory->name }}</a>
                </button>
                </h2>
                @endif

            <div id="collapseSubcategory{{ $loop->parent->index }}{{ $loop->index }}" class="accordion-collapse collapse" aria-labelledby="headingSubcategory{{ $loop->parent->index }}{{ $loop->index }}" data-bs-parent="#accordionSubcategories{{ $loop->parent->index }}">
            <div class="accordion-body ps-4">
            @if ($subCategory->childCategories->count() > 0)
            <!-- Child Categories List -->
            <ul class="list-group">
            @foreach ($subCategory->childCategories as $childCategory)
            @if ($childCategory->status == 1)
            <li class="list-group-item border-0 ps-0">
            <a class="categoryForSearch d-block py-1" href="javascript:;" data-private_child_category="{{ $childCategory->slug }}">{{ $childCategory->name }}</a>
            </li>
            @endif
            @endforeach
            </ul>
            @endif
            </div>
            </div>
            </div>
            @endforeach
            </div>
            @endif
            </div>
            </div>
            </div>
            @endforeach
            </div>
            </ul>
            <input type="hidden" name="private_category" value="" id="private_category_id_for_search">
            <input type="hidden" name="private_sub_category" value="" id="private_sub_category_id_for_search">
            <input type="hidden" name="private_child_category" value="" id="private_child_category_id_for_search">
            <input type="hidden" name="page_view" value="grid_view" id="page_view_id">
            </div>
            </div>
            </div>



     {{-- Desktop View  --}}
    <div class="accordion-item  d-none d-lg-block">
    <h2 class="accordion-header" id="headingOne">
    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
    {{__('user.Filter By Categories')}}
    </button>
    </h2>
    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
    <div class="desktop-menu">
    <ul class="wsus_menu_cat_item">
    <!-- Main Categories -->
    @foreach ($productPrivateCategories as $category)
    <li class="category-item">
    <a class="categoryForSearch wsus__droap_arrow" href="javascript:;" data-private_category="{{ $category->slug }}">
    <i class="{{ $category->icon }}" aria-hidden="true"></i>
    {{ $category->name }}
    </a>
    @if ($category->private_subCategories->count() > 0)
    <ul class="wsus_menu_cat_droapdown">
    <!-- Subcategories -->
    @foreach ($category->private_subCategories as $subCategory)
    @if ($subCategory->status == 1)
    <li class="sub-category-item">
        <a class="categoryForSearch wsus__droap_arrow" href="javascript:;" data-private_sub_category="{{ $subCategory->slug }}">
        {{ $subCategory->name }}
        </a>
    @if ($subCategory->childCategories->count() > 0)
    <ul class="child-category-dropdown">
    <!-- Child Categories -->
    @foreach ($subCategory->childCategories as $childCategory)
    @if ($childCategory->status == 1)
    <li>
    <a
    class="categoryForSearch"
    href="javascript:;" data-private_child_category="{{ $childCategory->slug }}">
    {{ $childCategory->name }}
    </a>
    </li>
    @endIf
    @endforeach
    </ul>
    @endif
    </li>
    @endif

    @endforeach
    </ul>
    @endif
    </li>
    @endforeach
    </ul>
    </div>
    <!-- Hidden inputs -->
    <input type="hidden" name="private_category" value="" id="private_category_id_for_search_desktop">
    <input type="hidden" name="private_sub_category" value="" id="private_sub_category_id_for_search_desktop">
    <input type="hidden" name="private_child_category" value="" id="private_child_category_id_for_search_desktop">
    <input type="hidden" name="page_view" value="grid_view" id="page_view_id">
    </div>
    </div>






















                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree3">
                                  <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree3" aria-expanded="false" aria-controls="collapseThree">
                                      {{__('user.Filter by Brands')}}
                                  </button>
                                </h2>
                                <div id="collapseThree3" class="accordion-collapse collapse show" aria-labelledby="headingThree3" data-bs-parent="#accordionExample">
                                    <div class="col-12 p-3">
                                     <input type="text" id="searchBrands" class="form-control animated-element" placeholder="Search By Brands..">
                                    </div>
                                    <div class="accordion-body">
                                      @foreach ($brands as $brand)
                                        <div class="form-check">
                                            <input name="brands[]" class="form-check-input brand_item" type="checkbox" value="{{ $brand->id }}" id="flexCheckDefault11-{{ $brand->id }}">
                                            <label class="form-check-label" for="flexCheckDefault11-{{ $brand->id }}">
                                            {{ $brand->name }}
                                            </label>
                                        </div>
                                      @endforeach
                                  </div>
                                </div>
                              </div>

                            <div class="accordion-item">
                              <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    {{__('user.Filter by Price')}}
                                </button>
                              </h2>
                              <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="price_ranger">
                                        <input value="0;{{ $shop_page->filter_price_range }}" type="hidden" name="price_range" id="slider_range" class="flat-slider" />
                                        <button  type="submit" class="common_btn">{{__('user.filter')}}</button>
                                    </div>
                                </div>
                              </div>
                            </div>
                          <div class="accordion-item">
                                <h2 class="accordion-header" id="headingFour">
                                  <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                     Filter By Product Condition
                                  </button>
                                </h2>
                                <div id="collapseFour" class="accordion-collapse collapse show" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                            @foreach ($ads as $ad)
                            @if (!$ad == null)
                            <div class="form-check">
                                <input name="AdType[]" class="form-check-input brand_item" type="checkbox" value="{{ $ad->id }}" id="flexCheckDefault11-{{ $ad->id }}">
                                <label class="form-check-label" for="flexCheckDefault11-{{ $ad->id }}">
                                {{ $ad->name }}
                                </label>
                            </div>
                            @endif
                                      @endforeach
                                  </div>
                                </div>
                              </div>
                            {{-- @foreach ($variantsForSearch as $variantForSearch)
                                <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree2-{{ $variantForSearch->id }}">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree2-{{ $variantForSearch->id }}" aria-expanded="false" aria-controls="collapseThree">
                                        {{ $variantForSearch->name }}
                                    </button>
                                </h2>

                                <div id="collapseThree2-{{ $variantForSearch->id }}" class="accordion-collapse collapse show" aria-labelledby="headingThree2-{{ $variantForSearch->id }}" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        @php84
                                            $variantItemsForSearch = App\Models\ProductVariantItem::groupBy('name')->select('name','id')->where('product_variant_name', $variantForSearch->name)->get();
                                        @endphp

                                        @foreach ($variantItemsForSearch as $index => $variantItemForSearch)
                                            <div class="form-check">
                                                <input class="form-check-input variant_item_search" type="checkbox" name="variantItems[]" value="{{ $variantItemForSearch->name }}" id="{{ $variantForSearch->id }}-{{ $variantItemForSearch->id }}-{{ $variantItemForSearch->name }}">
                                                <label class="form-check-label" for="{{ $variantForSearch->id }}-{{ $variantItemForSearch->id }}-{{ $variantItemForSearch->name }}">
                                                {{ $variantItemForSearch->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                </div>
                            @endforeach --}}
                        </div>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-8">
                    <div class="row">
                        <div class="col-xl-12 d-none d-md-block mt-md-4 mt-lg-0">
                            <div class="wsus__product_topbar">
                                <div class="wsus__product_topbar_left">

                                    <div class="nav nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                        <button onclick="setPageView('grid_view')" class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true">
                                            <i class="fas fa-th"></i>
                                        </button>
                                        <button onclick="setPageView('list_view')" class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">
                                            <i class="fas fa-list-ul"></i>
                                        </button>
                                    </div>

                                </div>
                                {{-- <div class="wsus__topbar_select">
                                    <select class="select_2 shorting_id" name="shorting_id">
                                        <option value="1">{{__('user.default shorting')}}</option>
                                        <option value="2">{{__('user.low to high price')}} </option>
                                        <option value="3">{{__('user.high to low price')}}</option>
                                    </select>
                                </div> --}}
                            </div>
                        </div>
                    </form>
                        <div id="loadeer-hidden-content" class="d-none">
                            <div class="row">
                                <div class="col-12 mt-3">
                                    <div class="preloader">
                                        <img src="{{ asset('user/images/gif.gif') }}" alt="loader" class="img-fluid w-100">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-content load_ajax_response" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">

                                <div class="row">
                                    <div class="col-12 mt-3">
                                        <div class="preloader">
                                            <img src="{{ asset('user/images/gif.gif') }}" alt="loader" class="img-fluid w-100">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                <div class="row">
                                    <div class="col-12 mt-3">
                                        <div class="preloader">
                                            <img src="{{ asset('user/images/gif.gif') }}" alt="loader" class="img-fluid w-100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        PRODUCT USED PAGE END
    ==============================-->




<script>
    (function($) {
        "use strict";
        $(document).ready(function () {
            loadProductUsingAjax();
            $(".categoryForSearch").on("click", function(){
                let categoryId = $(this).data('category');
                $("#category_id_for_search").val(categoryId);
                submitSearchForm()
            })



            // Hover Menu
            $('.desktop-menu .category-item').on('mouseover', function(){
                $(this).addClass('active_cat-menu')
                $('div#v-pills-home').addClass('menu_hover')
            });
            $('.desktop-menu  .category-item').on('mouseleave', function(){
                $(this).removeClass('active_cat-menu')
                $('div#v-pills-home').removeClass('menu_hover')
            });



            // Private Category, Sub-Category, and Child Category Search Filter
                $(".categoryForSearch").on("click", function(){
                let categoryId = $(this).data('private_category');
                let subID = $(this).data('private_sub_category');
                let childID = $(this).data('private_child_category');

                // Reset all inputs
                // for mobile
                $("#private_category_id_for_search").val("");
                $("#private_sub_category_id_for_search").val("");
                $("#private_child_category_id_for_search").val("");

                // for desktop
                $("#private_category_id_for_search_desktop").val("");
                $("#private_sub_category_id_for_search_desktop").val("");
                $("#private_child_category_id_for_search_desktop").val("");

                // Mobile
                if (categoryId !== undefined) {
                $("#private_category_id_for_search").val(categoryId);
                }

                if (subID !== undefined) {
                $("#private_sub_category_id_for_search").val(subID);
                }

                if (childID !== undefined) {
                $("#private_child_category_id_for_search").val(childID);
                }


                // Desktop

                if (categoryId !== undefined) {
                $("#private_category_id_for_search_desktop").val(categoryId);
                }

                if (subID !== undefined) {
                $("#private_sub_category_id_for_search_desktop").val(subID);
                }

                if (childID !== undefined) {
                $("#private_child_category_id_for_search_desktop").val(childID);
                }

                submitSearchForm();
                });


            $("#searchProductFormId").on("submit", function(e){
                e.preventDefault();
                let loader = $("#loadeer-hidden-content").html();
                $('.load_ajax_response').html(loader);
                $.ajax({
                    type: 'get',
                    data: $('#searchProductFormId').serialize(),
                    url: "{{ route('search-used-product') }}",
                    success: function (response) {
                        $('.load_ajax_response').html(response);
                    },
                    error: function(err) {}
                });
            })

            $(".brand_item").on("click", function(){
                submitSearchForm();
            })

            $(".variant_item_search").on("click", function(){
                submitSearchForm();
            })

            $(".shorting_id").on("change", function(){
                submitSearchForm();
            })



            // Search Work
                let value = {
                country: 1,
                state: "",
                city: "",
                input_value: ""
                };

               //   Country Select
            $.ajax({
            type:"get",
            url:`/private/search-filter/state-by-country/${1}`,
            success:function(response){
            $("#state_id").html(response.states);
            $("#city_id").html("<option value=''>City</option>");
            },
            error:function(err){
            console.table(err);
            }
            })


        $("#state_id").on("change",function(e){
        e.preventDefault();
        let stateId = $("#state_id").val();
        const stateName = $("#state_id option:selected").data('name');
        if (stateId === "") {
            loadAllProducts();
        }
        if (stateId) {
        $("#resetInput").show();
    } else {
        $("#resetInput").hide();
    }
        value.state = stateId;
        if(stateId){
        $.ajax({
            type:"get",
            url: `/private/search-filter/city-by-state/${stateId}`,
            success:function(response){
                $("#city_id").html(response.cities);
            },
            error:function(err){
                console.table(err);
            }
        })
        }else{
        $("#city_id").html("<option value=''>City</option>");
        }

            })

            $("#city_id").on("change", function() {
            let cityId = $("#city_id").val();
            const cityName = $("#city_id option:selected").data('name');
             if (cityId === "") {
            loadAllProducts();
        }
            if (cityId) {
        $("#resetInput").show();
    } else {
        $("#resetInput").hide();
    }
                value.city = cityId ?? "";
        });

        // input Value

$("#searchInput").on("input", function () {
    const inputValue = $(this).val();
    if (inputValue) {
        $("#resetInput").show();
    } else {
        $("#resetInput").hide();
    }
    value.input_value = inputValue;
});


        // Reset the input and show all products when reset icon is clicked
        $("#resetInput").click(function () {
        $("#searchInput").val('');
        $("#state_id").val('');
         $("#city_id").val('');
        $(this).hide();
        value.input_value = '';
         value.state = '';
         value.city = '';
        loadAllProducts();
        });

        // Button Click

        $("#searchButton").click(function (e) {
            e.preventDefault();
            $.ajax({
                type: "get",
                url: "/search-private-used-product",
                data:{
                    values: value,
                },
                success: function (response) {
                  $('.load_ajax_response').html(response);
                }
            });

        });

    // Deault Reset the filter
    function loadAllProducts() {
    $.ajax({
        type: "get",
        url: "/search-private-used-product",
        data: {
            values: {
                country: 1,
                state: "",
                city: "",
                input_value: ""
             },
        },
        success: function (response) {
            $('.load_ajax_response').html(response);
        }
    });
}



        // Search By Brands
            $('#searchBrands').on('keyup', function() {
      var value = $(this).val().toLowerCase();
      $('.form-check').each(function() {
        var brandName = $(this).text().toLowerCase();
        if (brandName.indexOf(value) > -1) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });
    });


        });
    })(jQuery);

    function loadAjaxProduct(url){
        let loader = $("#loadeer-hidden-content").html();
        $('.load_ajax_response').html(loader);

        let pageView = $("#page_view_id").val();
        var href = new URL(url);
        href.searchParams.set('page_view', pageView);
        query_url = href.toString()
        $.ajax({
            type: 'get',
            url: query_url,
            success: function (response) {
                $('.load_ajax_response').html(response);
            },
            error: function(err) {}
        });
    }

    function setPageView(view){
        $("#page_view_id").val(view);
    }

    function loadProductUsingAjax(){
        let currentURL = window.location.href
        let index = currentURL.indexOf("?");
        currentURL = currentURL.substr(index+1)
        let url = "{{ url('search-used-product') }}" + "?" + currentURL;
        console.log(url);
        $.ajax({
            type: 'get',
            url: url,
            success: function (response) {
                $('.load_ajax_response').html(response);
            },
            error: function(err) {}
        });
    }

    // Filter By Side bar
    function submitSearchForm(){
        let loader = $("#loadeer-hidden-content").html();
        $('.load_ajax_response').html(loader);

        $.ajax({
            type: 'get',
            data: $('#searchProductFormId').serialize(),
            url: "{{ route('search-used-product') }}",
            success: function (response) {
                $('.load_ajax_response').html(response);
            },
            error: function(err) {console.error(err)}
        });
    }

            $(function() {
                createSticky($(".animated-container"));
            });
            function createSticky(sticky) {
                if (typeof sticky !== "undefined") {
                    // Check if the window width is 768px or less (commonly considered as mobile screen size)
                    if ($(window).width() <= 768) {
                        var pos = sticky.offset().top + 20,
                            win = $(window);
                        win.on("scroll", function() {
                            if (win.scrollTop() >= pos) {
                                sticky.addClass("fixed");
                            } else {
                                sticky.removeClass("fixed");
                            }
                        });
                    }
                }
            }
</script>
@endsection
