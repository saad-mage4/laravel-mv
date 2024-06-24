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
        <form id="registrationForm">
              <div class="row mb-3" id="list-styled">
              <h1 class="mb-4 fs-5 text-uppercase">private registration form</h1>
              <div class="col-xl-6 col-md-6">
                    <label for="FirstName">First Name</label>
                    <div class="wsus__dash_pro_single ">
                      <div class="flex-column">
                        <input id="FirstName" type="text" id="FirstName" placeholder="First Name" name="firstName" value="" required  />
                    <span id="firstNameError" class="error"></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="LastName">Last Name</label>
                    <div class="wsus__dash_pro_single">
                        <div class="flex-column">
                      <input id="LastName" type="text" id="LastName" placeholder="Last Name" name="lastName" value="" required  />
                        <span id="lastNameError" class="error"></span>
                        </div>
                    </div>
                  </div>

                  <div class="col-xl-6 col-md-6">
                    <label for="email">Email</label>
                    <div class="wsus__dash_pro_single">
                        <div class="flex-column">
                      <input type="email" id="email" placeholder="{{__('user.Email')}}" name="email" value="" required />
                    <span id="emailError" class="error"></span>
                        </div>
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="Phone Number">Phone Number</label>
                    <div class="wsus__dash_pro_single">
                        <div class="flex-column">
                      <input id="phoneNo" type="number" placeholder="{{__('user.Phone')}}" name="phone" value="" required  />
                      <span id="phoneNoError" class="error"></span>
                        </div>
                    </div>
                  </div>
          </div>
          <div class="col-xl-12 d-flex justify-content-md-end justify-content-center">
             <button id="submitButton" type="submit" class="common_btn mb-4 mt-2 next">
                                    <div id="loader" class="loader" style="display:none;"></div> <!-- Loader element -->
                                    Submit
                                </button>
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

    let value = {
        firstName: "",
        lastName:"",
        email:"",
        phone: ""
      };

       // Initialize jQuery Validation Plugin
    // $('#registrationForm').validate({
    //     rules: {
    //         firstName: "required",
    //         lastName: "required",
    //         email: {
    //             required: true,
    //             email: true
    //         },
    //         phone: {
    //             required: true,
    //             digits: true
    //         }
    //     },
    //     messages: {
    //         firstName: "Please enter your first name",
    //         lastName: "Please enter your last name",
    //         email: {
    //             required: "Please enter your email",
    //             email: "Please enter a valid email address"
    //         },
    //         phone: {
    //             required: "Please enter your phone number",
    //             digits: "Please enter a valid phone number"
    //         }
    //     },
    //     errorPlacement: function (error, element) {
    //         let name = element.attr("name");
    //         $(`#${name}Error`).text(error.text());
    //     },
    //     success: function (label, element) {
    //         let name = $(element).attr("name");
    //         $(`#${name}Error`).text("");
    //     }
    // });


      $('#registrationForm input').change(function (e) {
        e.preventDefault();
        const { name, value: inputValue } = e.target;
            value[name] = inputValue;
            console.log(inputValue);
            if (inputValue === "") {
                $(`#${name}Error`).text("This field is required");
            } else {
                $(`#${name}Error`).text("");
            }
            console.log("change",value);
      });


      $('#registrationForm').submit(function (e) {
        e.preventDefault();
        const validate = $("#registrationForm").validate();
        // let formdata = $(this).serialize();
        if (validate.valid()) {
               $.ajax({
                type: "get",
                url: "/user/private-seller-request",
                data:{
                    values: value,
                },
                success: function (response) {
                    //  window.location.href = response;

                }
            });
      }
        })


    });
  })(jQuery);
</script>



@endsection
