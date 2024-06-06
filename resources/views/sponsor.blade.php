@extends('layout')
@section('title')
    <title>Sponsor Page</title>
@endsection
@section('meta')
    <meta name="description" content="">
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

@section('public-content')

<div class="container sponsor-page">
      <div class="row">
            <div class="col-12 my-5">
                  <h1 class="text-center text-uppercase">Sponsor Page</h1>
            </div>
            <!-- Top Main Image -->
            @foreach($bannerPositions as $position => $size)
                  @php
                  $banner = $banners->firstWhere('banner_position', $position);
                  $isBooked = $banner ? (bool)$banner->is_booked : false;
                  $width = $banner->width ?? '';
                  $height = $banner->height ?? '';
                  $imageUrl = $isBooked ? URL::asset($banner->image_url) : "https://dummyimage.com/{$size}/dbdbdb/000000.jpg&text=Coming+Soon";
                  $sponsorUrl = $isBooked ? $banner->banner_redirect : "";
                  @endphp

                  <div class="col-{{ $position == 'first_image' || $position == 'fifth_image' ? '12' : '4' }} mt-3 my-5">
                  @if($isBooked)
                        <a href="/{{$sponsorUrl}}">
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

@endsection
