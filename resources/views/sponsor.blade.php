@extends('layout')
@section('title')
    <title>Sponsor Page</title>
@endsection
@php
$img1 = true;
$img2 = true;
$img3 = true;
@endphp
@section('meta')
    <meta name="description" content="">
@endsection

@section('public-content')

<div class="container sponsor-page">
      <div class="row">
            <div class="col-12 my-5">
                  <h1 class="text-center text-uppercase">Sponsor Page</h1>
            </div>
            <!-- Top Main Image -->
            <div class="col-12">
                  @if($img1 === true)
                        <a href="#!">
                              <img src="https://picsum.photos/1280/500" alt="img-1">
                        </a>
                  @else
                        <img src="https://dummyimage.com/1280x500/ffc1a6/000000.jpg" alt="dummy-1">
                  @endif
            </div>
            <!-- 3 Images Section -->
            <div class="col-4 my-5">
                  @if($img2 === true)
                        <a href="#!">
                              <img src="https://picsum.photos/350/700" alt="img-2">
                        </a>
                  @else
                        <img src="https://dummyimage.com/350x700/ffc1a6/000000.jpg" alt="dummy-1">
                  @endif
            </div>
            <div class="col-4 my-5">
                  @if($img2 === true)
                        <a href="#!">
                              <img src="https://picsum.photos/350/700" alt="img-3">
                        </a>
                  @else
                        <img src="https://dummyimage.com/350x700/ffc1a6/000000.jpg" alt="dummy-1">
                  @endif
            </div>
            <div class="col-4 my-5">
                  @if($img2 === true)
                        <a href="#!">
                              <img src="https://picsum.photos/350/700" alt="img-4">
                        </a>
                  @else
                        <img src="https://dummyimage.com/350x700/ffc1a6/000000.jpg" alt="dummy-1">
                  @endif
            </div>
            <!-- Middle Single Image -->
            <div class="col-12">
                  @if($img3 === true)
                        <a href="#!">
                              <img src="https://picsum.photos/1280/200" alt="img-1">
                        </a>
                  @else
                        <img src="https://dummyimage.com/1280x200/ffc1a6/000000.jpg" alt="dummy-1">
                  @endif
            </div>
            <!-- 3 Images Section -->
            <div class="col-4 my-5">
                  @if($img2 === true)
                        <a href="#!">
                              <img src="https://picsum.photos/350/700" alt="img-2">
                        </a>
                  @else
                        <img src="https://dummyimage.com/350x700/ffc1a6/000000.jpg" alt="dummy-1">
                  @endif
            </div>
            <div class="col-4 my-5">
                  @if($img2 === true)
                        <a href="#!">
                              <img src="https://picsum.photos/350/700" alt="img-3">
                        </a>
                  @else
                        <img src="https://dummyimage.com/350x700/ffc1a6/000000.jpg" alt="dummy-1">
                  @endif
            </div>
            <div class="col-4 my-5">
                  @if($img2 === true)
                        <a href="#!">
                              <img src="https://picsum.photos/350/700" alt="img-4">
                        </a>
                  @else
                        <img src="https://dummyimage.com/350x700/ffc1a6/000000.jpg" alt="dummy-1">
                  @endif
            </div>
      </div>
</div>

@endsection
