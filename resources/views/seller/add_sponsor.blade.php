@extends('seller.master_layout')
@section('title')
<title>{{__('user.Add Sponsor')}}</title>
@endsection
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
      <div class="section-body">
            <div class="row">
                  <div class="col-12">
                        test
                  </div>
            </div>
      </div>
</section>
</div>
@endsection;