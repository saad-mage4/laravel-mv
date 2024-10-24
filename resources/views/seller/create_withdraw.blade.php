@extends('seller.master_layout')
@section('title')
<title>{{__('user.My withdraw')}}</title>
@endsection
@section('seller-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('user.My withdraw')}}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('seller.dashboard') }}">{{__('user.Dashboard')}}</a></div>
              <div class="breadcrumb-item">{{__('user.My withdraw')}}</div>
            </div>
          </div>

          <div class="section-body">
            <div class="d-flex justify-content-between align-items-center gap-3">
                <a href="{{ route('seller.my-withdraw.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> {{__('user.My withdraw')}}</a>
            {{-- <h5 class="text-black btn btn-primary">Avaiable Amount : <span class="fs-4 font-weight-bold">{{$currentAmount}}</span></h5> --}}
            </div>
            <div class="row mt-4">
                <div class="col-6">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('seller.my-withdraw.store') }}" method="POST">
                        @csrf
                        {{-- <div class="form-group">
                            <label for="">{{__('user.Withdraw Method')}} </label>
                            <select name="method_id" id="method_id" class="form-control">
                                <option value="">{{__('user.Select Method')}}</option>
                                @foreach ($methods as $method)
                                    <option value="{{ $method->id }}">{{ $method->name }}</option>
                                @endforeach
                            </select>
                        </div> --}}

                        <div class="form-group">
                            <label for="">{{__('user.Withdraw Amount')}}</label>
                            <input type="text" class="form-control" name="withdraw_amount" required>
                        </div>


                        <div class="form-group">
                            <label for="">{{__('user.Account Information')}}</label>
                            <textarea name="account_info" id="" cols="30" rows="10" class="form-control text-area-5" required></textarea>
                        </div>

                        <button class="btn btn-primary" type="submit">{{__('user.Send Request')}}</button>
                        </form>
                    </div>
                  </div>
                </div>

                <div class="col-6 d-none" id="method_des_box">
                    <div class="card">
                        <div class="card-body" id="method_des">

                        </div>
                    </div>
                </div>
          </div>
        </section>
      </div>



<script>
    (function($) {
    "use strict";
    $(document).ready(function () {
        $("#method_id").on('change', function(){
            var methodId = $(this).val();
            $.ajax({
                type:"get",
                url:"{{url('/seller/get-withdraw-account-info/')}}"+"/"+methodId,
                success:function(response){
                   $("#method_des").html(response)
                   $("#method_des_box").removeClass('d-none')
                },
                error:function(err){}
            })

            if(!methodId){
                $("#method_des_box").addClass('d-none')
            }

        })
    });

    })(jQuery);
</script>
@endsection
