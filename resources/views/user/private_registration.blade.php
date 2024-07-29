@extends('user.layout')
@section('title')
<title>{{__('user.Become a Seller')}}</title>
@endsection
@section('user-content')
<?php
$countries = App\Models\Country::orderBy('name','asc')->where('status',1)->get();
$states = App\Models\CountryState::orderBy('name','asc')->where(['status' => 1, 'country_id' => 0])->get();
$cities = App\Models\City::orderBy('name','asc')->where(['status' => 1, 'country_state_id' => 0])->get();
?>
<div class="row">
  <form id="registrationForm">
    <div class="col-12 col-md-8 mx-auto">
    <div class="dashboard_content mt-2 mt-md-0">
    <div class="wsus__dashboard_profile">
        <div class="wsus__dash_pro_area">
              <div class="row mb-3" id="list-styled">
              <h1 class="mb-4 fs-5 text-uppercase">private registration form</h1>
              <div class="col-xl-6 col-md-6">
                    <label for="FirstName">First Name</label>
                    <div class="wsus__dash_pro_single ">

                        <input type="text" id="FirstName" placeholder="First Name" name="firstName"   />

                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="LastName">Last Name</label>
                    <div class="wsus__dash_pro_single">

                      <input type="text" id="LastName" placeholder="Last Name" name="lastName"   />


                    </div>
                  </div>

                  <div class="col-xl-6 col-md-6">
                    <label for="email">Email</label>
                    <div class="wsus__dash_pro_single">

                      <input type="email" id="email" placeholder="{{__('user.Email')}}" name="email"  />

                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="phone">Phone Number</label>
                    <div class="wsus__dash_pro_single">

                      <input id="phone" type="tel" placeholder="{{__('user.Phone')}}" name="phone"   />


                    </div>
                  </div>

            <div class="col-xl-6 col-md-6">
                                        <div class="wsus__dash_pro_single">
                                            <select class="form-select" style="padding: 12px 10px;" name="country" id="country_id">
                                                <option value="">{{__('user.Select Country')}}*</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}" data-name="{{ $country->name }}">{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6">
                                        <div class="wsus__dash_pro_single">
                                            <select class="form-select" style="padding: 12px 10px;" name="state" id="state_id">
                                                <option value="">{{__('user.Select State')}}</option>
                                                @foreach ($states as $state)
                                                    <option value="{{ $state->id }}" data-name="{{ $state->name }}">{{ $state->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6">
                                        <div class="wsus__dash_pro_single">
                                            <select class="form-select" style="padding: 12px 10px;" name="city" id="city_id">
                                                <option value="">{{__('user.Select City')}}</option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city->id }}" data-name="{{ $city->name }}">{{ $city->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

          </div>
          <div class="col-xl-12 d-flex justify-content-md-end justify-content-center">
             <button id="submitButton" type="submit" class="common_btn mb-4 mt-2 next">
             <div id="loader" class="loader" style="display:none;"></div>
                                    <span class="text-white">Submit</span>
                                </button>
              </div>
        </div>
</div>
</div>
    </div>
</form>
</div>

<script>

  (function($) {
    "use strict";
    $(document).ready(function() {

    let value = {
        firstName: "",
        lastName:"",
        email:"",
        phone: "",
        country: "",
        state: "",
        city: ""
      };


      $('#registrationForm input').change(function (e) {
        e.preventDefault();
        const { name, value: inputValue } = e.target;
            value[name] = inputValue;
            if (inputValue === "") {
                $(`#${name}Error`).text("This field is required");
            } else {
                $(`#${name}Error`).text("");
            }
      });


    //   Country Select
    $("#country_id").on("change",function(e){
        e.preventDefault();
                let countryId = $("#country_id").val();
                let countryName = $("#country_id option:selected").data('name');
                value.country = countryName;
                if(countryId){
                    $.ajax({
                        type:"get",
                        url:"{{url('/user/private/state-by-country/')}}"+"/"+countryId,
                        success:function(response){
                            console.log(response);
                            $("#state_id").html(response.states);
                            $("#city_id").html("<option value=''>{{__('user.Select a City')}}</option>");
                        },
                        error:function(err){
                            console.table(err);
                        }
                    })
                }else{
                    $("#state_id").html("<option value=''>{{__('user.Select a State')}}</option>");
                    $("#city_id").html("<option value=''>{{__('user.Select a City')}}</option>");
                }

            })

            $("#state_id").on("change",function(e){
                e.preventDefault();
                let stateId = $("#state_id").val();
                const stateName = $("#state_id option:selected").data('name');
                value.state = stateId;
                if(stateId){
                    $.ajax({
                        type:"get",
                        url:"{{url('/user/private/city-by-state/')}}"+"/"+stateId,
                        success:function(response){
                            $("#city_id").html(response.cities);
                        },
                        error:function(err){
                            console.table(err);
                        }
                    })
                }else{
                   $("#city_id").html("<option value=''>{{__('user.Select a City')}}</option>");
                }

            })


        $("#city_id").on("change", function() {
        let cityId = $("#city_id").val();
        const cityName = $("#city_id option:selected").data('name');
          value.city = cityId ?? "";
        });


      $('#registrationForm').submit(function (e) {
        e.preventDefault();

  $('#registrationForm').validate({
        rules: {
            firstName: "required",
            lastName: "required",
            email: {
                required: true,
                email: true
            },
            phone: {
                required: true,
                digits: true
            },
            country: "required",
            state: "required",
            city: "required"
        },
        messages: {
            firstName: "Please enter your first name",
            lastName: "Please enter your last name",
            email: {
                required: "Please enter your email",
                email: "Please enter a valid email address"
            },
            phone: {
                required: "Please enter your phone number",
                digits: "Please enter a valid phone number"
            },
            country: {
                required: "Please select a country"
            },
      state: {
        required :"Please select a state"
      },
      city: {
        required :"Please select a city"
      }
        }
});
        console.log('test: ',$("#registrationForm").valid());
        if ($("#registrationForm").valid()) {
               $.ajax({
                type: "get",
                url: "/user/private-seller-request",
                data:{
                    values: value,
                },
                beforeSend: function(){
                  $('#submitButton span').text("");
                  $('#loader').show();
                },
                success: function (response) {
                  $('#submitButton span').text("Submit");
                  $('#loader').hide();
                     window.location.href = response;
                },
                error: function(xhr) {
            $('#submitButton span').text("Submit");
            $('#loader').hide();
            if (xhr.status === 400) {
                alert(xhr.responseJSON.message);
            } else {
                alert("An error occurred. Please try again.");
            }
        }
            });
      }
        })


    });
  })(jQuery);
</script>



@endsection
