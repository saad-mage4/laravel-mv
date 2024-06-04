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
                                    <a href="#!">
                                          <img src="https://picsum.photos/1280/500" alt="img-1">
                                    </a>
                              @else
                                    <a href="#!" class="first_image" data-slot="first_image" data-toggle="modal" data-target="#add-sponsor-modal">
                                    <img src="https://dummyimage.com/1280x500/dbdbdb/000000.jpg&text=Slot+Available" alt="dummy-1">
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

<!-- Modal -->
<div class="modal fade" id="add-sponsor-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <form id="add-sponsor-form" method="post" enctype="multipart/form-data">
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
                  <input
                  type="text"
                  name="prod_link"
                  id="prod-link"
                  class="form-control"
                  required
                  />
                  </div>
            </div>
            <div class="col-12">
                  <div class="custom-file">
                  <input
                  type="file"
                  name="banner_img"
                  class="custom-file-input"
                  id="banner-img"
                  required
                  />
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
