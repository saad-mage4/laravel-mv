@extends('user.layout')
@section('title')
    <title>{{__('user.Dashboard')}}</title>
@endsection
@section('user-content')
<div class="row">
     @if ($setting->enable_multivendor == 1)
                @php
                    $authUser = Auth::guard('web')->user();
                    $isSeller = App\Models\Vendor::where('user_id', $authUser->id)->first();
                     $Check_Status = $isSeller->status ?? 1; //if the vender is delete we add by default satats 1

                @endphp
               @if ($Check_Status == 0)
               <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto text-center alert alert-info">
              <h3 class="mb-2">Your Request has been submitted</h3>
              <p class="text-dark fs-5">Kindly wait for admin approvel, <strong>this might take 24 hours</strong></p>
               </div>
               @else
                @endif
    @endif
    <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
      <div class="dashboard_content">
        <div class="wsus__dashboard">
          <div class="row">
            <div class="col-lg-4 col-6 col-md-4">
                <a class="wsus__dashboard_item blue" href="{{ route('user.order') }}">
                  <i class="fas fa-shopping-cart"></i>
                  <p>{{__('user.Total Order')}}</p>
                  <span class="text-white">{{ $orders->count() }}</span>
                </a>
              </div>
            <div class="col-lg-4 col-6 col-md-4">
                <a class="wsus__dashboard_item purple" href="{{ route('user.complete-order') }}">
                  <i class="fas fa-shopping-cart"></i>
                  <p>{{__('user.Complete Order')}}</p>
                  <span class="text-white">{{ $orders->where('order_status',3)->count() }}</span>
                </a>
              </div>

            <div class="col-lg-4 col-6 col-md-4">
              <a class="wsus__dashboard_item green" href="{{ route('user.pending-order') }}">
                <i class="fas fa-shopping-cart"></i>
                <p>{{__('user.Pending Order')}}</p>
                <span class="text-white">{{ $orders->where('order_status',0)->count() }}</span>
              </a>
            </div>

            <div class="col-lg-4 col-6 col-md-4">
                <a class="wsus__dashboard_item red" href="{{ route('user.declined-order') }}">
                  <i class="fas fa-shopping-cart"></i>
                  <p>{{__('user.Declined Order')}}</p>
                  <span class="text-white">{{ $orders->where('order_status',4)->count() }}</span>
                </a>
              </div>

            <div class="col-lg-4 col-6 col-md-4">
              <a class="wsus__dashboard_item sky" href="{{ route('user.review') }}">
                <i class="fas fa-star"></i>
                <p>{{__('user.Review')}}</p>
                <span class="text-white">{{ $reviews->count() }}</span>
              </a>
            </div>
            <div class="col-lg-4 col-6 col-md-4">
              <a class="wsus__dashboard_item blue" href="{{ route('user.wishlist') }}">
                <i class="far fa-heart"></i>
                <p>{{__('user.Wishlist')}}</p>
                <span class="text-white">{{ $wishlists->count() }}</span>
              </a>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
