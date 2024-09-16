<div  class="tab-pane fade {{ $page_view == 'grid_view' ? 'show active' : '' }} " id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
    @if ($products->count() == 0)
        <div class="row">
            <div class="col-12 text-center">
                <h3 class="text-danger mt-5">{{__('user.Product not found')}}</h3>
            </div>
        </div>
    @endif
    {{--  --}}
    <div class="row">
        @foreach ($products as $product)
        @if ($product->status == 1)
        <div class="col-xl-4  col-sm-6">
            <div class="wsus__product_item">
                @if ($product->new_product == 1)
                    <span class="wsus__new">{{__('user.New')}}</span>
                @elseif ($product->is_featured == 1)
                    <span class="wsus__new">{{__('user.Featured')}}</span>
                @elseif ($product->is_top == 1)
                    <span class="wsus__new">{{__('user.Top')}}</span>
                @elseif ($product->is_best == 1)
                    <span class="wsus__new">{{__('user.Best')}}</span>
                @endif

                {{-- @if ($isCampaign)
                    <span class="wsus__minus">-{{ $campaignOffer }}%</span>
                @else
                    @if ($product->offer_price != null)
                        <span class="wsus__minus">-{{ $percentage }}%</span>
                    @endif
                @endif --}}
                <a class="wsus__pro_link" href="{{ route('product_used_detail', $product->slug) }}">
                    <img src="{{ asset($product->thumb_image) }}" alt="product" class="img-fluid w-100 img_1" />
                    <img src="{{ asset($product->thumb_image) }}" alt="product" class="img-fluid w-100 img_2" />
                </a>
                {{-- <ul class="wsus__single_pro_icon">
                    <li><a data-bs-toggle="modal" data-bs-target="#productModalView-{{ $product->id }}"><i class="fal fa-eye"></i></a></li>
                    <li><a href="javascript:;" onclick="addToWishlist('{{ $product->id }}')"><i class="far fa-heart"></i></a></li>
                    <li><a href="javascript:;" onclick="addToCompare('{{ $product->id }}')"><i class="far fa-random"></i></a></li>
                </ul> --}}
                {{-- {{dd($ads)}} --}}
                <div class="wsus__product_details">
                <a class="wsus__category" href="#!">
                    @foreach ($ads as $ad)
                        @if ($product->private_ad_type == $ad->id)
                            {{$ad->name}}
                        @else

                        @endif
                    @endforeach
                </a>

                {{-- @if ($reviewQty > 0)
                    <p class="wsus__pro_rating">
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
                        <span>({{ $reviewQty }} {{__('user.review')}})</span>
                    </p>
                @endif
                @if ($reviewQty == 0)
                    <p class="wsus__pro_rating">
                        <i class="fal fa-star"></i>
                        <i class="fal fa-star"></i>
                        <i class="fal fa-star"></i>
                        <i class="fal fa-star"></i>
                        <i class="fal fa-star"></i>
                        <span>(0 {{__('user.review')}})</span>
                    </p>
                @endif --}}
                <h4 class="View_Price"> {{ $currencySetting->currency_icon }}<span id="mainProductPrice">{{$product->price}}</span>
                    {{-- <del>${{$product->offer_price}}</del> --}}
                </h4>
                    <a class="wsus__pro_name" href="{{ route('product_used_detail',$product->slug) }}">{{ $product->name }}</a>

                        {{-- <p class="">{{ $product->short_description }}</p> --}}
                     {{-- <a class="add_cart" onclick="addToCartMainProduct('{{ $product->id }}')" href="javascript:;">{{__('user.add to cart')}}</a> --}}
                    <a class="add_cart position-static mt-2"  href="tel:{{$product->phone}}">Call</a>
                    <a class="add_cart position-static mt-2"  href="tel:{{$product->phone}}">Chat</a>
                </div>
            </div>
        </div>
        @endif
        @endforeach

        <div class="col-xl-12">
            {{ $products->links('ajax_custom_paginator') }}
        </div>
    </div>
</div>
<div  class="tab-pane fade {{ $page_view == 'list_view' ? 'show active' : '' }}" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
    @if ($products->count() == 0)
        <div class="row">
            <div class="col-12 text-center">
                <h3 class="text-danger mt-5">{{__('user.Product not found')}}</h3>
            </div>
        </div>
    @endif
    <div class="row">
        @foreach ($products as $product)
        @if ($product->status == 1)
        <div class="col-xl-12">
            <div class="wsus__product_item wsus__list_view">
                @if ($product->new_product == 1)
                    <span class="wsus__new">{{__('user.New')}}</span>
                @elseif ($product->is_featured == 1)
                    <span class="wsus__new">{{__('user.Featured')}}</span>
                @elseif ($product->is_top == 1)
                    <span class="wsus__new">{{__('user.Top')}}</span>
                @elseif ($product->is_best == 1)
                    <span class="wsus__new">{{__('user.Best')}}</span>
                @endif

                {{-- @if ($isCampaign)
                    <span class="wsus__minus">-{{ $campaignOffer }}%</span>
                @else
                    @if ($product->offer_price != null)
                        <span class="wsus__minus">-{{ $percentage }}%</span>
                    @endif
                @endif --}}

                <a class="wsus__pro_link" style="height: 320px;" href="{{ route('product_used_detail', $product->slug) }}">
                    <img src="{{ asset($product->thumb_image) }}" alt="product" class="img-fluid w-100 img_1" />
                    <img src="{{ asset($product->thumb_image) }}" alt="product" class="img-fluid w-100 img_2" />
                </a>
                <div class="wsus__product_details ">
                    <a class="wsus__category" href="{{ route('used_products',['category' => $product->categorySlug]) }}">{{ $product->CategoryName }} </a>

                {{-- @if ($reviewQty > 0)
                    <p class="wsus__pro_rating">
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
                        <span>({{ $reviewQty }} {{__('user.review')}})</span>
                    </p>
                @endif
                @if ($reviewQty == 0)
                    <p class="wsus__pro_rating">
                        <i class="fal fa-star"></i>
                        <i class="fal fa-star"></i>
                        <i class="fal fa-star"></i>
                        <i class="fal fa-star"></i>
                        <i class="fal fa-star"></i>
                        <span>(0 {{__('user.review')}})</span>
                    </p>
                @endif --}}
                <h4 class="View_Price"> {{ $currencySetting->currency_icon }} <span id="mainProductPrice">{{$product->price}}</span>
                    {{-- <del>${{$product->offer_price}}</del> --}}
                </h4>
                    <a class="wsus__pro_name" href="{{ route('product_used_detail',$product->slug) }}">{{ $product->name }}</a>
                    <p class="" style="max-height: 80px; overflow-y: auto">{{ $product->short_description }}</p>
                    <ul class="wsus__single_pro_icon mt-4">
                        <li><a class="add_cart"  href="tel:{{$product->phone}}">Call</a></li>
                    <li><a class="add_cart"  href="tel:{{$product->phone}}">Chat</a></li>
                       {{-- <li><a class="add_cart" onclick="addToCartMainProduct('{{ $product->id }}')" href="javascript:;">{{__('user.add to cart')}}</a></li>
                         <li><a href="javascript:;" onclick="addToWishlist('{{ $product->id }}')"><i class="far fa-heart"></i></a></li>
                        <li><a href="javascript:;" onclick="addToCompare('{{ $product->id }}')"><i class="far fa-random"></i></a></li> --}}
                    </ul>
                </div>
            </div>
        </div>
        @endif
        @endforeach
        <div class="col-xl-12">
            {{ $products->links('ajax_custom_paginator') }}
        </div>
    </div>
</div>


@foreach ($products as $product)



    <script src="{{ asset('user/js/slick.min.js') }}"></script>
    <script src="{{ asset('user/js/jquery.exzoom.js') }}"></script>

<script>
    (function($) {
        "use strict";
        $(document).ready(function () {
            $('.modal_slider').not('.slick-initialized').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 1000,
                dots: false,
                arrows: true,
                nextArrow: '<i class="fas fa-chevron-right nxt_arr"></i>',
                prevArrow: '<i class="fas fa-chevron-left prv_arr"></i>',
            });

            $('.banner_slider').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 4000,
                dots: true,
                arrows: false,
            });

            $('.select_2').select2();

            $(".productModalVariant").on("change", function(){
                let id = $(this).data("product");
                calculateProductModalPrice(id);
            })

        });
    })(jQuery);
</script>
@endforeach
