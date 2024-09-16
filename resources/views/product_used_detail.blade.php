@extends('layout')
@section('title')
    <title>{{ $product->seo_title }}</title>
@endsection
@section('meta')
    <meta name="description" content="{{ $product->seo_description }} {{ $tags }}">
@endsection

@section('public-content')


    <!--============================
         BREADCRUMB START
    ==============================-->
    <section id="wsus__breadcrumb" style="background: url({{  asset($product->banner_image) }});">
        <div class="wsus_breadcrumb_overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h4>{{__('user.Product')}}</h4>
                        <ul>
                            <li><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                            <li><a href="{{ route('product') }}">{{__('user.Product')}}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        BREADCRUMB END
    ==============================-->
<!--============================
        productDETAILS START
    ==============================-->
    <section id="wsus__product_details">
        <div class="container">
            <div class="row">
                {{-- col-xl-4 col-md-5 col-lg-5 --}}
                {{-- View product --}}
                <div class="col-12 col-md-8">
                    <div id="sticky_pro_zoom">
                        <div class="exzoom hidden" id="exzoom">
                            <div class="exzoom_img_box">
                                @if ($product->video_link)
                                    @php
                                        $video_id=explode("=",$product->video_link);
                                    @endphp
                                    <a class="venobox wsus__pro_det_video" data-autoplay="true" data-vbtype="video"
                                        href="https://youtu.be/{{ $video_id[1] }}">
                                        <i class="fas fa-play"></i>
                                    </a>
                                @endif
                                <ul class='exzoom_img_ul'>
                                    @foreach ($gallery as $image)
                                    @if ($image->count() === 1 &&  $image->status === 1)
                                  <li><img class="zoom img-fluid w-100" src="{{ asset($image->first()->image) }}" alt="product"></li>
                                    @else <li><img class="zoom ing-fluid w-100" src="{{ asset($image->image) }}" alt="product"></li>
                                    @endif
                                    @endforeach


                                </ul>
                            </div>
                          @if ($gallery->count() > 1 )
                        <div class="exzoom_nav"></div>
                        <p class="exzoom_btn">
                            <a href="javascript:void(0);" class="exzoom_prev_btn"> <i class="far fa-chevron-left"></i> </a>
                            <a href="javascript:void(0);" class="exzoom_next_btn"> <i class="far fa-chevron-right"></i> </a>
                        </p>
                        @endif


                        </div>
                    </div>
                </div>
                {{-- seller information  --}}
                <div class="col-12 col-md-4" >
                      @if ($product->vendor_id != 0)
                            @php
                             $users = Auth::guard('web')->user();
                                $user = $product;
                                // $user = $user->user;
                            @endphp
                  <div class="wsus__pro_det_vendor p-4 rounded-3" style="border: 2px solid #d8dfe0">
                                    {{-- <div class="row"> --}}
                                        <div class="col-xl-4 col-xxl-5 col-md4">
                                            <div class="wsus__vebdor_img h-auto rounded-circle fa-w-20">
                                                {{-- {{dd($user)}} --}}
                                                @if ($user->Vendor_banner)
                                                <img src="{{ asset($user->Vendor_banner) }}" alt="vendor-db" class="img-fluid w-100" style="width: 20px">
                                                @else
                                                <img src="{{ asset($defaultProfile->image) }}" alt="vendor-default" class="img-fluid w-100" style="width: 20px">
                                                @endif

                                            </div>
                                        </div>
                                       <div class="col-xl-12 col-xxl-12 col-md-12 mt-5 mt-md-0">
                                            <div class="wsus__pro_det_vendor_text w-100">
                                                <h4 class="mt-3">{{ $user->firstName }} {{ $user->lastName }}</h4>
                                                    {{-- @php
                                                        $reviewQty = App\Models\ProductReview::where('status',1)->where('product_vendor_id',$product->vendor_id)->count();
                                                        $totalReview = App\Models\ProductReview::where('status',1)->where('product_vendor_id',$product->vendor_id)->sum('rating');
                                                        if ($reviewQty > 0) {
                                                            $average = $totalReview / $reviewQty;
                                                            $intAverage = intval($average);
                                                            $nextValue = $intAverage + 1;
                                                            $reviewPoint = $intAverage;
                                                            $halfReview = false;
                                                            if($intAverage < $average && $average < $nextValue){
                                                                $reviewPoint= $intAverage + 0.5;
                                                                $halfReview=true;
                                                            }
                                                        }
                                                    @endphp --}}

                                                    {{-- @if ($reviewQty > 0)
                                                    <p class="rating">
                                                        @for ($i = 1; $i <=5; $i++)
                                                            @if ($i <= $reviewPoint)
                                                                <i class="fas fa-star"></i>
                                                            @elseif ($i> $reviewPoint )
                                                                @if ($halfReview==true)
                                                                <i class="fas fa-star-half-alt"></i>
                                                                    @php
                                                                        $halfReview=false
                                                                    @endphp
                                                                @else
                                                                <i class="fal fa-star"></i>
                                                                @endif
                                                            @endif
                                                        @endfor
                                                        <span>({{ $reviewQty }} {{ __('user.review') }})</span>
                                                    </p>
                                                    @endif

                                                    @if ($reviewQty == 0)
                                                        <p class="rating">
                                                            <i class="fal fa-star"></i>
                                                            <i class="fal fa-star"></i>
                                                            <i class="fal fa-star"></i>
                                                            <i class="fal fa-star"></i>
                                                            <i class="fal fa-star"></i>
                                                            <span>(0 {{ __('user.review') }})</span>
                                                        </p>
                                                    @endif --}}
                                                 {{-- {{ dd($user) }} --}}
                                                <p><span class="w-auto">{{__('user.Store Name')}}:</span> {{ $user->shop_name ?? "test Store" }}</p>
                                                {{-- <p><span class="w-auto">{{__('user.Address')}}:</span> {{ $user->address }} {{ $user->city ? ','.$user->city->name : '' }} {{ $user->city ? ','.$user->city->countryState->name : '' }} {{ $user->city ? ','.$user->city->countryState->country->name : '' }}</p> --}}
                                                <p><span class="w-auto">{{__('user.Phone')}}:</span> {{ $product->phone }}</p>
                                                <p><span class="w-auto">Country:</span> {{ $product->country }}</p>
                                                <p><span class="w-auto">City:</span> {{ $product->City_Name }}</p>
                                                <p><span class="w-auto">State:</span> {{ $product->State_Name }}</p>
                                                <p><span class="w-auto">{{__('user.mail')}}:</span> {{ $user->email }}</p>
                                                <div class="d-flex gap-3">
                                                    <a href="{{ route('seller_used_detail',['shop_name' => $user->Vendor_Slug]) }}" class="see_btn d-flex justify-content-center align-items-center">{{__('user.visit store')}}</a>
                                                    @if ($user->seller_type == "Private")
                                                    <a href="{{ route('user.chat-with-private-seller', $user->Vendor_Slug) }}" class="see_btn">{{__('user.Chat with Seller')}}</a>
                                                    @else
                                                    <a href="{{ route('user.chat-with-seller', $user->Vendor_Slug) }}" class="see_btn d-flex justify-content-center align-items-center">{{__('user.Chat with Seller')}}</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="col-xl-12">
                                            <div class="wsus__vendor_details">
                                                {!! clean($user->vendorDescription) !!}
                                            </div>
                                        </div> --}}
                                    </div>
                                {{-- </div> --}}
                @endif
                {{-- Return Data  --}}
                 {{-- <div class="col-xl-3 col-md-12 mt-md-5 mt-lg-0"> --}}
                    {{-- id="sticky_sidebar" --}}
                    {{-- <div class="wsus_pro_det_sidebar" >
                        <div class="lg_area">
                            <div class="wsus_pro_det_sidebar_single">
                                <i class="fal fa-truck"></i>
                                <div class="wsus_pro_det_sidebar_text">

                                    @if ($product->is_return == 1)
                                    <h5>{{__('user.Return Available')}}</h5>
                                    <p>{{ $product->returnPolicy->details }}</p>
                                    @else
                                        <h5>{{__('user.Return Not Available')}}</h5>
                                    @endif

                                </div>
                            </div>


                            <div class="wsus_pro_det_sidebar_single">
                                <i class="far fa-shield-check"></i>
                                <div class="wsus_pro_det_sidebar_text">
                                    <h5>{{__('user.Secure Payment')}}</h5>
                                    <p>{{__('user.We ensure secure payment')}}</p>
                                </div>
                            </div>
                            <div class="wsus_pro_det_sidebar_single">
                                <i class="fal fa-envelope-open-dollar"></i>
                                <div class="wsus_pro_det_sidebar_text">
                                    @if ($product->is_warranty == 1)
                                    <h5>{{__('user.Warranty Available')}}</h5>
                                    @else
                                    <h5>{{__('user.Warranty Not Available')}}</h5>
                                    @endif

                                </div>
                            </div>
                        </div>

                        @if ($banner->status == 1)
                            <div class="wsus__det_sidebar_banner">
                                <img src="{{ asset($banner->image) }}" alt="banner" class="img-fluid w-100">
                                    <div class="wsus__det_sidebar_banner_text_overlay">
                                    <div class="wsus__det_sidebar_banner_text">
                                        <p>{{ $banner->title }}</p>
                                        <h4>{{ $banner->description }}</h4>
                                        <a href="{{ $banner->link }}" class="common_btn">{{__('user.shop now')}}</a>
                                    </div>
                                </div>
                            </div>
                        @endif


                    </div> --}}
                {{-- </div> --}}
                </div>
                {{-- col-xl-5 col-md-7 col-lg-7 --}}
                <div class="col-12 mt-5">
                    <div class="wsus__pro_details_text p-3 rounded-3 " style="border: 2px solid #d8dfe0; ">
                        <a class="title" href="javascript:;">{{ $product->name }}</a>
                        <h4> {{ $currencySetting->currency_icon }} <span id="mainProductPrice">{{$product->price}}</span>
                            {{-- <del>${{$product->offer_price}}</del> --}}
                        </h4>
                            {{-- Review Code  --}}

                        <p class="description" style="max-height: 200px; overflow-y: auto">{{ $product->short_description }}</p>

                        {{-- Flash Deal  --}}



                        <form id="shoppingCartForm">
                        {{-- <div class="wsus__quentity">
                            <h5>{{__('user.Quantity')}} :</h5>
                            <div class="modal_btn">
                                <button type="button" class="btn btn-danger btn-sm decrementProduct">-</button>
                                <input class="form-control product_qty" name="quantity" readonly type="text" min="1" max="{{ $product->qty }}" value="1" data-qty="{{ $product->qty }}"/>
                                <button type="button" class="btn btn-success btn-sm incrementProduct">+</button>
                            </div>
                            <h3 class="d-none">{{ $currencySetting->currency_icon }}<span id="product_price">{{ sprintf("%.2f",$productPrice) }}</span></h3>
                        </div> --}}

                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="image" value="{{ $product->thumb_image }}">
                        <input type="hidden" name="slug" value="{{ $product->slug }}">

                        {{-- productVariants --}}
                        <ul class="wsus__button_area">
                            {{-- <li><button type="submit" class="add_cart">{{__('user.add to cart')}}</button></li>
                            <li><a class="buy_now" href="javascript:;" id="buyNowBtn">{{__('user.buy now')}}</a></li>
                            <li><a href="javascript:;" onclick="addToWishlist('{{ $product->id }}')"><i class="fal fa-heart"></i></a></li>
                            <li><a href="javascript:;" onclick="addToCompare('{{ $product->id }}')"><i class="far fa-random"></i></a></li> --}}

                    <li><a class="add_cart"  href="tel:{{$product->phone}}">Call</a></li>
                    <li><a class="add_cart"  href="tel:{{$product->phone}}">Chat</a></li>
                        </ul>

                    </form>
                        @if ($product->sku)
                        <p class="brand_model detaile_private_seller"><span>{{__('user.Model')}} :</span> {{ $product->sku }}</p>
                        @endif

                        <p class="brand_model detaile_private_seller" ><span>{{__('user.Brand')}} :</span>
                            {{-- <a href="{{ route('product',['brand' => $product->brandSlug]) }}">{{ $product->brandName }}</a></p> --}}
                            {{ $product->brandName }}
                        <p class="brand_model detaile_private_seller"><span>Ad Type :</span>
                            {{-- <a href="{{ route('product',['category' => $product->categorySlug]) }}">{{ $product->CategoryName }}</a> --}}
                            {{-- {{$product->private_ad_type}} --}}
                            @foreach ($ads as $ad)
                        @if ($product->private_ad_type == $ad->id)
                            {{$ad->name}}
                        @else

                        @endif
                    @endforeach
                        </p>
                        <div class="wsus__pro_det_share d-none">
                            <h5>{{__('user.share')}} :</h5>
                            <ul class="d-flex">
                                <li><a class="facebook" href="https://www.facebook.com/sharer/sharer.php?u={{ route('product-detail', $product->slug) }}&t={{ $product->name }}"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a class="twitter" href="https://twitter.com/share?text={{ $product->name }}&url={{ route('product-detail', $product->slug) }}"><i class="fab fa-twitter"></i></a></li>
                                <li><a class="linkedin" href="https://www.linkedin.com/shareArticle?mini=true&url={{ route('product-detail', $product->slug) }}&title={{ $product->name }}"><i class="fab fa-linkedin"></i></a></li>
                                <li><a class="pinterest" href="https://www.pinterest.com/pin/create/button/?description={{ $product->name }}&media=&url={{ route('product-detail', $product->slug) }}"><i class="fab fa-pinterest-p"></i></a></li>
                            </ul>
                        </div>
                        {{-- @auth
                            @php
                                $user = Auth::guard('web')->user();
                                $isExist = false;
                                $orders = App\Models\Order::where(['user_id' => $user->id])->get();
                                foreach ($orders as $key => $order) {
                                    foreach ($order->orderproduct as $key => $orderProduct) {
                                        if($orderProduct->product_id == $product->id){
                                            $isExist = true;
                                        }
                                    }
                                }
                            @endphp
                        @if ($isExist)
                            <a class="wsus__pro_report" href="#" data-bs-toggle="modal" data-bs-target="#productReportModal"><i
                            class="fal fa-comment-alt-smile"></i> {{__('user.Report incorrect productinformation')}}</a>
                        @endif

                        @endauth --}}

                    </div>

                    <!--==========================
                    product REPORT MODAL VIEW
                    ===========================-->
                    {{-- @auth
                        @if ($isExist)
                            <section class="product_popup_modal report_modal">
                                <div class="modal fade" id="productReportModal" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">{{__('user.Report Product')}}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                                                        class="far fa-times"></i></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <form id="reportModalForm">
                                                            @csrf
                                                            <div class="wsus__single_input">
                                                                <label>{{__('user.Subject')}}</label>
                                                                <input type="text" name="subject" placeholder="{{__('user.Type Subject')}}">
                                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                                <input type="hidden" name="seller_id" value="{{ $product->vendor_id }}">
                                                            </div>
                                                            <div class="wsus__single_input">
                                                                <label>{{__('user.Description')}}</label>
                                                                <textarea name="description" cols="3" rows="3"
                                                                    placeholder="{{__('user.Description')}}"></textarea>
                                                            </div>

                                                            <button type="submit" class="common_btn">{{__('user.submit')}}</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @endif
                    @endauth --}}
                    <!--==========================
                    productREPORT MODAL VIEW
                    ===========================-->
                </div>

                {{-- Warranty  --}}
                {{-- <div class="col-xl-3 col-md-12 mt-md-5 mt-lg-0">
                    <div class="wsus_pro_det_sidebar" id="sticky_sidebar">
                        <div class="lg_area">
                            <div class="wsus_pro_det_sidebar_single">
                                <i class="fal fa-truck"></i>
                                <div class="wsus_pro_det_sidebar_text">

                                    @if ($product->is_return == 1)
                                    <h5>{{__('user.Return Available')}}</h5>
                                    <p>{{ $product->returnPolicy->details }}</p>
                                    @else
                                        <h5>{{__('user.Return Not Available')}}</h5>
                                    @endif

                                </div>
                            </div>


                            <div class="wsus_pro_det_sidebar_single">
                                <i class="far fa-shield-check"></i>
                                <div class="wsus_pro_det_sidebar_text">
                                    <h5>{{__('user.Secure Payment')}}</h5>
                                    <p>{{__('user.We ensure secure payment')}}</p>
                                </div>
                            </div>
                            <div class="wsus_pro_det_sidebar_single">
                                <i class="fal fa-envelope-open-dollar"></i>
                                <div class="wsus_pro_det_sidebar_text">
                                    @if ($product->is_warranty == 1)
                                    <h5>{{__('user.Warranty Available')}}</h5>
                                    @else
                                    <h5>{{__('user.Warranty Not Available')}}</h5>
                                    @endif

                                </div>
                            </div>
                        </div>

                        @if ($banner->status == 1)
                            <div class="wsus__det_sidebar_banner">
                                <img src="{{ asset($banner->image) }}" alt="banner" class="img-fluid w-100">
                                    <div class="wsus__det_sidebar_banner_text_overlay">
                                    <div class="wsus__det_sidebar_banner_text">
                                        <p>{{ $banner->title }}</p>
                                        <h4>{{ $banner->description }}</h4>
                                        <a href="{{ $banner->link }}" class="common_btn">{{__('user.shop now')}}</a>
                                    </div>
                                </div>
                            </div>
                        @endif


                    </div>
                </div> --}}

                {{-- Description Shown  --}}
                                    {{-- <div class="col-12">
                                        <div class="wsus__pro_det_description">
                                            <h1 class="fw-bold  mb-3">Description :</h1>
                                            {!! $product->long_description !!}
                                        </div>
                                    </div> --}}

                <div class="col-xl-12">
                    <div class="wsus__pro_det_description">
                        <ul class="nav nav-pills mb-3" id="pills-tab3" role="tablist">
                            <li class="nav-item <?= $product->is_specification == 1 ? 'w-50' : 'w-100' ?>" role="presentation">
                                <button class="nav-link active" id="pills-home-tab7" data-bs-toggle="pill"
                                    data-bs-target="#pills-home22" type="button" role="tab" aria-controls="pills-home"
                                    aria-selected="true">{{__('user.Description')}}</button>
                            </li>
                            @if ($product->is_specification == 1)
                            <li class="nav-item w-50" role="presentation">
                                <button class="nav-link" id="pills-profile-tab7" data-bs-toggle="pill"
                                    data-bs-target="#pills-profile22" type="button" role="tab"
                                    aria-controls="pills-profile" aria-selected="false">{{__('user.Specification')}}</button>
                            </li>
                            @endif

                            {{-- @if ($product->vendor_id != 0)
                            @if ($setting->enable_multivendor == 1)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-contact" type="button" role="tab"
                                    aria-controls="pills-contact" aria-selected="false">{{__('user.Seller Information')}}</button>
                            </li>
                            @endif
                            @endif --}}

                            {{-- <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-contact-tab2" data-bs-toggle="pill"
                                    data-bs-target="#pills-contact2" type="button" role="tab"
                                    aria-controls="pills-contact2" aria-selected="false">{{__('user.Reviews')}}</button>
                            </li> --}}

                        </ul>
                        <div class="tab-content" id="pills-tabContent4">
                            <div class="tab-pane fade  show active " id="pills-home22" role="tabpanel"
                                aria-labelledby="pills-home-tab7">
                                <div class="row">
                                    <div class="col-12">
                                        {!! $product->long_description !!}
                                    </div>


                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-profile22" role="tabpanel"
                                aria-labelledby="pills-profile-tab7">
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 mb-4 mb-lg-0">
                                        <div class="wsus__pro_det_info">
                                            <h4>{{__('user.Additional Information')}}</h4>
                                            {{-- @foreach ($product->specifications as $specification)
                                            <p><span>{{ $specification->key->key }}</span> <span>{{ $specification->specification }}</span></p>
                                            @endforeach --}}
                                        </div>
                                    </div>

                                </div>
                            </div>
                            @if ($product->vendor_id != 0)
                            @php
                                $user = $product;
                            @endphp
                            {{-- seller info  --}}
                            <div class="tab-pane fade" id="pills-contact" role="tabpanel"
                                aria-labelledby="pills-contact-tab">
                                <div class="wsus__pro_det_vendor">
                                    <div class="row">
                                        <div class="col-xl-6 col-xxl-5 col-md-6">
                                            <div class="wsus__vebdor_img">
                                                @if ($user->Vendor_banner)
                                                <img src="{{ asset($user->Vendor_banner) }}" alt="vensor" class="img-fluid w-100">
                                                @else
                                                <img src="{{ asset($defaultProfile->image) }}" alt="vensor" class="img-fluid w-100">
                                                @endif

                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-xxl-7 col-md-6 mt-4">
                                            <div class="wsus__pro_det_vendor_text">
                                                <h4>{{ $user->name }}</h4>
                                                @php
                                                    $reviewQty = App\Models\ProductReview::where('status',1)->where('product_vendor_id',$product->vendor_id)->count();
                                                    $totalReview = App\Models\ProductReview::where('status',1)->where('product_vendor_id',$product->vendor_id)->sum('rating');
                                                    if ($reviewQty > 0) {
                                                        $average = $totalReview / $reviewQty;
                                                        $intAverage = intval($average);
                                                        $nextValue = $intAverage + 1;
                                                        $reviewPoint = $intAverage;
                                                        $halfReview = false;
                                                        if($intAverage < $average && $average < $nextValue){
                                                            $reviewPoint= $intAverage + 0.5;
                                                            $halfReview=true;
                                                        }
                                                    }
                                                @endphp

                                                {{-- @if ($reviewQty > 0)
                                                <p class="rating">
                                                    @for ($i = 1; $i <=5; $i++)
                                                        @if ($i <= $reviewPoint)
                                                            <i class="fas fa-star"></i>
                                                        @elseif ($i> $reviewPoint )
                                                            @if ($halfReview==true)
                                                            <i class="fas fa-star-half-alt"></i>
                                                                @php
                                                                    $halfReview=false
                                                                @endphp
                                                            @else
                                                            <i class="fal fa-star"></i>
                                                            @endif
                                                        @endif
                                                    @endfor
                                                    <span>({{ $reviewQty }} {{ __('user.review') }})</span>
                                                </p>
                                                @endif

                                                @if ($reviewQty == 0)
                                                    <p class="rating">
                                                        <i class="fal fa-star"></i>
                                                        <i class="fal fa-star"></i>
                                                        <i class="fal fa-star"></i>
                                                        <i class="fal fa-star"></i>
                                                        <i class="fal fa-star"></i>
                                                        <span>(0 {{ __('user.review') }})</span>
                                                    </p>
                                                @endif --}}

                                                <p><span>{{__('user.Store Name')}}:</span> {{ $user->shop_name ?? '' }}</p>
                                                {{-- <p><span>{{__('user.Address')}}:</span> {{ $user->address }} {{ $user->city ? ','.$user->city->name : '' }} {{ $user->city ? ','.$user->city->countryState->name : '' }} {{ $user->city ? ','.$user->city->countryState->country->name : '' }}</p> --}}
                                                <p><span>{{__('user.Phone')}}:</span> {{ $user->phone }}</p>
                                                <p><span>{{__('user.mail')}}:</span> {{ $user->email }}</p>
                                                <a href="{{ route('seller_used_detail',['shop_name' => $user->Vendor_Slug]) }}" class="see_btn">{{__('user.visit store')}}</a>

                                                @if ($user->seller_type == "Private")
                                                    <a href="{{ route('user.chat-with-private-seller', $user->Vendor_Slug) }}" class="see_btn">{{__('user.Chat with Seller')}}</a>
                                                    @else
                                                    <a href="{{ route('user.chat-with-seller', $user->Vendor_Slug) }}" class="see_btn d-flex justify-content-center align-items-center">{{__('user.Chat with Seller')}}</a>
                                                    @endif
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <div class="wsus__vendor_details">
                                                {!! clean($user->vendorDescription) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="tab-pane fade" id="pills-contact2" role="tabpanel"
                                aria-labelledby="pills-contact-tab2">
                                <div class="wsus__pro_det_review">
                                    <div class="wsus__pro_det_review_single">
                                        <div class="row">
                                            <div class="col-xl-8 col-lg-7">
                                                <div class="wsus__comment_area">
                                                    <h4>{{__('user.Reviews')}} <span>{{ $totalProductReviewQty }}</span></h4>
                                                    @foreach ($productReviews as $review)
                                                    <div class="wsus__main_comment">
                                                        <div class="wsus__comment_img">
                                                            <img src="{{ $review->user->image ? asset($review->user->image) : asset($defaultProfile->image) }}" alt="user"
                                                                class="img-fluid w-100">
                                                        </div>
                                                        <div class="wsus__comment_text replay">
                                                            <h6>{{ $review->user->name }} <span>{{ $review->rating }} <i
                                                                        class="fas fa-star"></i></span></h6>
                                                            <span>{{ $review->created_at->format('d M, Y') }}</span>
                                                            <p>
                                                                {{ $review->review }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                    {{ $productReviews->links('custom_paginator') }}
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-lg-5 mt-4 mt-lg-0">
                                                <div class="wsus__post_comment rev_mar" id="sticky_sidebar3">
                                                    <h4>{{__('user.write a Review')}}</h4>
                                                    <form id="reviewFormId">
                                                        @csrf
                                                        <p class="rating">
                                                            <span>{{__('user.select your rating')}} : </span>
                                                            <i class="fas fa-star product_rat" data-rating="1" onclick="productReview(1)"></i>
                                                            <i class="fas fa-star product_rat" data-rating="2" onclick="productReview(2)"></i>
                                                            <i class="fas fa-star product_rat" data-rating="3" onclick="productReview(3)"></i>
                                                            <i class="fas fa-star product_rat" data-rating="4" onclick="productReview(4)"></i>
                                                            <i class="fas fa-star product_rat" data-rating="5" onclick="productReview(5)"></i>
                                                        </p>
                                                        <div class="row">
                                                            <div class="col-xl-12">
                                                                <div class="col-xl-12">
                                                                    <div class="wsus__single_com">
                                                                        <textarea name="review" cols="3" rows="3"
                                                                            placeholder="{{__('user.Write your review')}}"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <input type="hidden" id="product_id" name="product_id" value="{{ $product->id }}">
                                                        <input type="hidden" name="rating" value="5" id="product_rating">
                                                        <input type="hidden" name="seller_id" value="{{ $product->vendor_id }}">

                                                        @if($recaptchaSetting->status==1)
                                                            <div class="col-xl-12">
                                                                <div class="wsus__single_com mb-3">
                                                                    <div class="g-recaptcha" data-sitekey="{{ $recaptchaSetting->site_key }}"></div>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @auth
                                                        <button class="common_btn" type="submit">{{__('user.submit review')}}</button>
                                                        @else
                                                        <a class="login_link" href="{{ route('login') }}">{{__('user.Before submit review, please login first')}}</a>
                                                        @endauth

                                                    </form>
                                                </div>
                                            </div>
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
        productDETAILS END
    ==============================-->


        <!--============================
        RELATED productSTART
    ==============================-->

    <!--============================
        RELATED productEND
    ==============================-->


<script>
    (function($) {
        "use strict";
        $(document).ready(function () {
            $(".productVariant").on("change",function(){
                calculateProductPrice();
            })

            $(".decrementProduct").on("click", function(){
                let qty = $(".product_qty").val();
                if(qty > 1){
                    qty = qty - 1;
                    $(".product_qty").val(qty);
                    calculateProductPrice();
                }
            })

            $(".incrementProduct").on("click", function(){
                let stock_qty = "{{ $product->qty }}";

                let qty = $(".product_qty").val();
                if(parseInt(qty) < parseInt(stock_qty)){
                    qty = qty*1 + 1*1;
                    $(".product_qty").val(qty);
                    calculateProductPrice();
                }
            })

            $("#reportModalForm").on('submit', function(e){
                e.preventDefault();
                var isDemo = "{{ env('APP_VERSION') }}"
                if(isDemo == 0){
                    toastr.error('This Is Demo Version. You Can Not Change Anything');
                    return;
                }
                $.ajax({
                    type: 'post',
                    data: $('#reportModalForm').serialize(),
                    url: "{{ route('user.product-report') }}",
                    success: function (response) {
                        if(response.status == 1){
                            toastr.success(response.message)
                            $("#productReportModal").trigger("reset");
                            $("#productReportModal").modal('hide')
                        }
                        if(response.status == 0){
                            toastr.error(response.message)
                        }
                    },
                    error: function(err) {
                        alert('error')
                    }
                });
            })

            //start insert new cart item
            $("#shoppingCartForm").on("submit", function(e){
                e.preventDefault();
                $.ajax({
                    type: 'get',
                    data: $('#shoppingCartForm').serialize(),
                    url: "{{ route('add-to-cart') }}",
                    success: function (response) {
                        if(response.status == 0){
                            toastr.error(response.message)
                        }
                        if(response.status == 1){
                            toastr.success(response.message)
                            $.ajax({
                                type: 'get',
                                url: "{{ route('load-sidebar-cart') }}",
                                success: function (response) {
                                   $("#load_sidebar_cart").html(response)
                                   $.ajax({
                                        type: 'get',
                                        url: "{{ route('get-cart-qty') }}",
                                        success: function (response) {
                                            $("#cartQty").text(response.qty);
                                        },
                                    });
                                },
                            });
                        }
                    },
                    error: function(response) {

                    }
                });
            })
            //start insert new cart item

            // buy now item
            $("#buyNowBtn").on("click", function(){
                $.ajax({
                    type: 'get',
                    data: $('#shoppingCartForm').serialize(),
                    url: "{{ route('add-to-cart') }}",
                    success: function (response) {
                        if(response.status == 0){
                            toastr.error(response.message)
                        }
                        if(response.status == 1){
                            window.location.href = "{{ route('cart') }}";
                            toastr.success(response.message)
                            $.ajax({
                                type: 'get',
                                url: "{{ route('load-sidebar-cart') }}",
                                success: function (response) {
                                   $("#load_sidebar_cart").html(response)
                                   $.ajax({
                                        type: 'get',
                                        url: "{{ route('get-cart-qty') }}",
                                        success: function (response) {
                                            $("#cartQty").text(response.qty);
                                        },
                                    });
                                },
                            });
                        }
                    },
                    error: function(response) {

                    }
                });
            })

            $("#reviewFormId").on('submit', function(e){
                e.preventDefault();

                var isDemo = "{{ env('APP_VERSION') }}"
                if(isDemo == 0){
                    toastr.error('This Is Demo Version. You Can Not Change Anything');
                    return;
                }
                $.ajax({
                    type: 'post',
                    data: $('#reviewFormId').serialize(),
                    url: "{{ route('user.store-product-review') }}",
                    success: function (response) {
                        if(response.status == 1){
                            toastr.success(response.message)
                            $("#reviewFormId").trigger("reset");
                        }
                        if(response.status == 0){
                            toastr.error(response.message)
                            $("#reviewFormId").trigger("reset");
                        }
                    },
                    error: function(response) {
                        if(response.responseJSON.errors.rating)toastr.error(response.responseJSON.errors.rating[0])
                        if(response.responseJSON.errors.review)toastr.error(response.responseJSON.errors.review[0])
                        if(!response.responseJSON.errors.rating || !response.responseJSON.errors.review){
                            toastr.error("{{__('user.Please complete the recaptcha to submit the form')}}")
                        }
                    }
                });
            })

        });
    })(jQuery);

    function calculateProductPrice(){
        $.ajax({
            type: 'get',
            data: $('#shoppingCartForm').serialize(),
            url: "{{ route('calculate-product-price') }}",
            success: function (response) {
                let qty = $(".product_qty").val();
                let price = response.productPrice * qty;
                price = price.toFixed(2);
                $("#product_price").text(price);
                $("#mainProductPrice").text(price);
            },
            error: function(err) {
                alert('error')
            }
        });
    }

    function productReview(rating){
        $(".product_rat").each(function(){
            var product_rat = $(this).data('rating')
            if(product_rat > rating){
                $(this).removeClass('fas fa-star').addClass('fal fa-star');
            }else{
                $(this).removeClass('fal fa-star').addClass('fas fa-star');
            }
        })
        $("#product_rating").val(rating);
    }

</script>
@endsection
