@extends('seller.master_layout')
@section('title')
<title>{{__('user.Add Sponsor')}}</title>
@endsection
@php
$img1 = false;
$img2 = false;
$img3 = false;
$available = false;
@endphp
@section('seller-content')
<div class="main-content">
<section class="section">
      <div class="section-header">
            <h1>{{__('user.Add Sponsor')}}</h1>
            <div class="section-header-breadcrumb">
                  <div class="breadcrumb-item active"><a href="{{ route('seller.dashboard') }}">{{__('user.Dashboard')}}</a></div>
                  <div class="breadcrumb-item">{{__('user.Add Sponsor')}}</div>
            </div>
      </div>
      <div class="section-body add-sponsor">
            <div class="row">
                  <div class="col-8 mx-auto">
                  <div class="row">
                        <!-- Top Main Image -->
                        <div class="col-12 mt-3">
                              @if($available === true)
                                    <img src="https://dummyimage.com/1280x500/dbdbdb/000000.jpg&text=Slot+Available" alt="dummy-1">
                              @else
                                    <a href="#!">
                                          <img src="https://picsum.photos/1280/500" alt="img-1">
                                    </a>
                              @endif
                        </div>
                        <!-- 3 Images Section -->
                        <div class="col-4 my-5">
                              @if($img2 === true)
                                    <a href="#!">
                                          <img src="https://picsum.photos/350/700" alt="img-2">
                                    </a>
                              @else
                                    <img src="https://dummyimage.com/350x700/dbdbdb/000000.jpg&text=Slot+Available" alt="dummy-1">
                              @endif
                        </div>
                        <div class="col-4 my-5">
                              @if($img2 === true)
                                    <a href="#!">
                                          <img src="https://picsum.photos/350/700" alt="img-3">
                                    </a>
                              @else
                                    <img src="https://dummyimage.com/350x700/dbdbdb/000000.jpg&text=Slot+Available" alt="dummy-1">
                              @endif
                        </div>
                        <div class="col-4 my-5">
                              @if($img2 === true)
                                    <a href="#!">
                                          <img src="https://picsum.photos/350/700" alt="img-4">
                                    </a>
                              @else
                                    <img src="https://dummyimage.com/350x700/dbdbdb/000000.jpg&text=Slot+Available" alt="dummy-1">
                              @endif
                        </div>
                        <!-- Middle Single Image -->
                        <div class="col-12">
                              @if($img3 === true)
                                    <a href="#!">
                                          <img src="https://picsum.photos/1280/200" alt="img-1">
                                    </a>
                              @else
                                    <img src="https://dummyimage.com/1280x200/dbdbdb/000000.jpg&text=Slot+Available" alt="dummy-1">
                              @endif
                        </div>
                        <!-- 3 Images Section -->
                        <div class="col-4 my-5">
                              @if($img2 === true)
                                    <a href="#!">
                                          <img src="https://picsum.photos/350/700" alt="img-2">
                                    </a>
                              @else
                                    <img src="https://dummyimage.com/350x700/dbdbdb/000000.jpg&text=Slot+Available" alt="dummy-1">
                              @endif
                        </div>
                        <div class="col-4 my-5">
                              @if($img2 === true)
                                    <a href="#!">
                                          <img src="https://picsum.photos/350/700" alt="img-3">
                                    </a>
                              @else
                                    <img src="https://dummyimage.com/350x700/dbdbdb/000000.jpg&text=Slot+Available" alt="dummy-1">
                              @endif
                        </div>
                        <div class="col-4 my-5">
                              @if($img2 === true)
                                    <a href="#!">
                                          <img src="https://picsum.photos/350/700" alt="img-4">
                                    </a>
                              @else
                                    <img src="https://dummyimage.com/350x700/dbdbdb/000000.jpg&text=Slot+Available" alt="dummy-1">
                              @endif
                        </div>
                        </div>
                  </div>
            </div>
      </div>
</section>
</div>
@endsection;