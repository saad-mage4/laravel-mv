@extends('seller.master_layout')
@section('title')
    <title>{{__('user.Add Sponsor')}}</title>
@endsection
@php
    use Illuminate\Support\Facades\URL;

        $bannerPositions = [
           'first_image' => '1280x500',
           'second_image' => '350x700',
           'third_image' => '350x700',
           'fourth_image' => '350x700',
           'fifth_image' => '1280x200',
           'sixth_image' => '350x700',
           'seventh_image' => '350x700',
           'eighth_image' => '350x700'
       ];
@endphp
@section('seller-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{__('user.Add Sponsor')}}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a
                            href="{{ route('seller.dashboard') }}">{{__('user.Dashboard')}}</a></div>
                    <div class="breadcrumb-item">{{__('user.Add Sponsor')}}</div>
                </div>
            </div>
            <div class="section-body add-sponsor">
                <div class="row">
                    <div class="col-8 mx-auto">
                        <div class="row">
                            @foreach($bannerPositions as $position => $size)
                                @php
                                    $banner = $banners->firstWhere('banner_position', $position);
                                    $days = 10;
                                    $isBooked = $banner ? (bool)$banner->is_booked : false;
                                    $banner_user_id = $banner ? (int)$banner->sponsor_user_id : null;
                                    $width = $banner->width ?? '';
                                    $height = $banner->height ?? '';
                                    $imageUrl = $isBooked ? URL::asset($banner->image_url) : "https://dummyimage.com/{$size}/dbdbdb/000000.jpg&text=Slot+Available+Size+{$size}";
                                    $sponsorUrl = $isBooked ? $banner->banner_redirect : "";
                                @endphp
                                <div class="col-{{ $position == 'first_image' || $position == 'fifth_image' ? '12' : '4 h-700' }} mt-3 my-5">
                                @if($isBooked)
                                        <a class="viewSponsor {{ $banner_user_id == $userID ? 'own-this' : 'no-action' }}" href="/{{$sponsorUrl}}" data-position="{{$position}}" data-toggle="modal" data-target="#add-sponsor-modal" title="{{$days}}">
                                            <img src="{{ $imageUrl }}" width="{{$width}}" height="{{$height}}" alt="img-{{ $loop->index + 1 }}">
                                        </a>
                                    @else
                                        <a href="/{{$sponsorUrl}}" class="slot-images" data-slot="{{ $position }}" data-toggle="modal" data-target="#add-sponsor-modal">
                                            <img src="{{ $imageUrl }}" width="{{$width}}" height="{{$height}}" alt="dummy-{{ $loop->index + 1 }}">
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="add-sponsor-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <form id="add-sponsor-form" action="/seller/add-sponsor-req" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="prod-link">Product Link</label>
                                    <input type="text" name="prod_link" id="prod-link" class="form-control" required />
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="custom-file">
                                    <input type="file" name="banner_img" class="custom-file-input" id="banner-img" required />
                                    <label class="custom-file-label" for="banner-img">Choose file...</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="image_position" value="">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
