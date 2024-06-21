@extends('user.layout')
@section('title')
<title>{{__('user.Become a Seller')}}</title>
@endsection
@section('user-content')
<div class="row">
    <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
    <div class="dashboard_content mt-2 mt-md-0">
    <div class="wsus__dashboard_profile">
        <div class="wsus__dash_pro_area">
          <div class="row mb-3" id="list-styled">
              <h1 class="mb-4 fs-5 text-uppercase">private registration form</h1>
              <div class="col-xl-6 col-md-6">
                    <label for="FirstName">First Name</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" id="FirstName" placeholder="First Name" name="firstName" value="{{old('firstName')}}" required />
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="LastName">Last Name</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" id="LastName" placeholder="Last Name" name="lastName" value="{{old('lastName')}}" required />
                    </div>
                  </div>

                  <div class="col-xl-6 col-md-6">
                    <label for="email">Email</label>
                    <div class="wsus__dash_pro_single">
                      <input type="email" placeholder="{{__('user.Email')}}" name="email" required value="{{old('email')}}" />
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="Phone Number">Phone Number</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" placeholder="{{__('user.Phone')}}" name="phone" required value="{{old('phone')}}" />
                    </div>
                  </div>
          </div>
          <div id="Private_Registration_Submit" class="col-xl-12 d-flex justify-content-end">
                <a href="#!" class="common_btn mb-4 mt-2 next">Submit</a>
              </div>
        </div>
</div>
</div>
    </div>
</div>

<script>
  (function($) {
    "use strict";
    $(document).ready(function() {
      let value = [];
      $('#Private_Registration_Submit').click(function (e) {
        e.preventDefault();
        console.log("tahah");

        // $.ajax({
        //         type: "get",
        //         url: "/user/stripe-payment",
        //         data:{
        //             values: value,
        //         },
        //         success: function (response) {
        //              window.location.href = response;
        //         }
        //     });
      });

    });
  })(jQuery);
</script>

@endsection
