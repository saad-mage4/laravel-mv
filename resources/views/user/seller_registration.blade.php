@extends('user.layout')
@section('title')
<title>{{__('user.Become a Seller')}}</title>
@endsection
@section('user-content')
<link rel="stylesheet" href="{{ asset('user/css/seller_reg.css') }}">
<style>

</style>
<div class="row">
  <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
    <div class="dashboard_content mt-2 mt-md-0">
      <h3><i class="far fa-user"></i> {{__('user.Become a Seller')}}</h3>
      <div class="wsus__dashboard_profile mb-4 d-none">
        <div class="wsus__dash_pro_area">
          {!! $setting->seller_condition !!}
        </div>
      </div>

      <div class="wsus__dashboard_profile">
        <div class="wsus__dash_pro_area">
          <!-- Progress -->
          <div class="row">
            <div class="col-12">
              <ul id="progressbar">
                <li class="active" id="account">
                  <strong>Seller Information</strong>
                </li>
                <li id="personal">
                  <strong>Company Details & Payments</strong>
                </li>
                <li id="payment">
                  <strong>Elements & Legal Details</strong>
                </li>
                <li id="confirm"><strong>Product Range</strong></li>
                <!-- <li id="confirm"><strong>Operational Information</strong></li> -->
              </ul>
            </div>
          </div>
          <form id="multiStepsForm" action="{{ route('user.seller-request') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Step 1 -->
            <div class="row form_step">
              <div class="col-xl-12">
                <div class="row">
                  <div class="col-xl-6 col-md-6">
                    <label for="shop-name">Shop Name</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" id="shop-name" placeholder="{{__('user.Shop Name')}}" name="shop_name" required value="{{old('shop_name')}}" />
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="">{{__('user.Banner Image')}}</label>
                    <div class="wsus__dash_pro_single">
                      <input type="file" name="banner_image" required />
                    </div>
                  </div>
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
                  <div class="col-xl-6 col-md-6">
                    <label for="address">Address</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" id="address" placeholder="Address" name="address" value="{{old('address')}}" required />
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="open-at">Open At</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" placeholder="{{__('user.Opens at')}}" name="open_at" class="clockpicker" data-align="top" data-autoclose="true" autocomplete="off" value="{{old('open_at')}}" required />
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="close-at">Close At</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" placeholder="{{__('user.Closed at')}}" name="closed_at" class="clockpicker" data-align="top" data-autoclose="true" autocomplete="off" value="{{old('closed_at')}}" required />
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="postal-code">Postal Code</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" id="postal-code" placeholder="Postal Code" name="postalCode" value="{{old('postalCode')}}" required />
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="country">Company headquarters country location</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" id="country" name="country" class="form-control" value="Romania" readonly />
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="state_id">County</label>
                    <div class="wsus__dash_pro_single">
                      <select class="select_2" name="state" id="state_id" required>
                        <option value="{{(old('state') != null) ? old('state'):''}}" selected hidden>{{__('user.Select State')}}</option>
                        @foreach ($states as $state)
                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="city_id">Locality</label>
                    <div class="wsus__dash_pro_single">
                      <select class="select_2" name="city" id="city_id" required>
                        <option value="{{(old('city') != null) ? old('city'):''}}" selected hidden>{{__('user.Select City')}}</option>
                        @foreach ($cities as $city)
                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="company-name">Company name</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" id="company-name" placeholder="Company name" name="companyName" value="{{old('companyName')}}" required />
                    </div>
                  </div>

                  <div class="col-xl-6 col-md-6">
                    <label for="company-type">Company Type</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" id="company-type" placeholder="Company Type" name="companyType" value="{{old('companyType')}}" required />
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="urc">Unique Registration Code</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" id="urc" placeholder="Unique Registration Code" name="urc" value="{{old('urc')}}" required />
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="Vat-Payer">VAT Payer</label>
                    <div class="wsus__dash_pro_single gap-3">
                      <div class="radio-1">
                        <label for="vat-payer-1"></label>
                        <input type="radio" id="vat-payer-1" name="vat" required>
                      </div>
                      <div class="radio-2">
                        <label for="vat-payer-2"></label>
                        <input type="radio" id="vat-payer-2" name="vat" required checked>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-12 d-flex justify-content-end">
                <a href="#!" class="common_btn mb-4 mt-2 next">{{__('user.Next')}}</a>
                <!-- <button class="common_btn mb-4 mt-2" type="submit">{{__('user.Submit Request')}}</button> -->
              </div>
            </div>
            <!-- Step 2 -->
            <div class="row form_step d-none">
              <div class="col-xl-12">
                <div class="row">
                  <div class="col-12">
                    <h3>Payment Info</h3>
                    <div class="info">
                      <i class="fas fa-info"></i>
                      Info: All payments will be made in the local platform
                      currency (RON).
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="IBAN">IBAN</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" placeholder="IBAN" name="iban" required value="{{old('iban')}}">
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="Bank">Bank</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" placeholder="Bank" name="bank" required value="{{old('bank')}}">
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="SWIFT">SWIFT</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" placeholder="SWIFT" name="swift" required value="{{old('swift')}}">
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="Local-currency">Local currency (RON)</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" placeholder="Local-currency" name="localCurrency" required value="{{old('localCurrency')}}">
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="producer-label">I am a romanian producer?</label>
                    <div class="wsus__dash_pro_single gap-3">
                      <div class="radio-1">
                        <label for="producer-1"></label>
                        <input type="radio" id="producer-1" name="producer" required checked>
                      </div>
                      <div class="radio-2">
                        <label for="producer-2"></label>
                        <input type="radio" id="producer-2" name="producer" required>
                      </div>
                    </div>
                  </div>
                  <!-- <div class="col-md-12">
                    <label for="company-desc">Brief or short company description</label>
                    <div class="wsus__dash_pro_single">
                      <textarea type="text" id="company-desc" placeholder="Brief or short company description" name="companyDesc"></textarea>
                    </div>
                  </div> -->
                  <div class="col-xl-12">
                    <label for="company-desc">Brief or short company description</label>
                    <div class="wsus__dash_pro_single">
                      <textarea cols="3" rows="5" name="about" placeholder="Brief or short company description" required>{{old('about')}}</textarea>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-12 d-flex justify-content-between">
                <a href="#!" class="common_btn mb-4 mt-2 prev">{{__('user.Previous')}}</a>
                <a href="#!" class="common_btn mb-4 mt-2 next">{{__('user.Next')}}</a>
                <!-- <button class="common_btn mb-4 mt-2" type="submit">{{__('user.Submit Request')}}</button> -->
              </div>

            </div>
            <!-- Step 3 -->
            <div class="row form_step d-none">
              <div class="col-xl-12">
                <div class="row">
                  <div class="col-12 col-lg-3">
                    <label for="certificateRegistration">Certificate of registration</label>
                    <div class="wsus__dash_pro_single">
                      <input type="file" id="certificateRegistration" name="certificateRegistration" required>
                    </div>
                  </div>
                  <div class="col-12 col-lg-3">
                    <label for="idCardSignatory">ID Card of Signatory</label>
                    <div class="wsus__dash_pro_single">
                      <input type="file" id="idCardSignatory" name="idCardSignatory" required>
                    </div>
                  </div>
                  <div class="col-12 col-lg-3">
                    <label for="BankStatement">Bank Statement</label>
                    <div class="wsus__dash_pro_single">
                      <input type="file" id="BankStatement" name="bankStatement" required>
                    </div>
                  </div>
                  <div class="col-12 col-lg-3">
                    <label for="ArticlesOfIncorporation">Articles of Incorporation</label>
                    <div class="wsus__dash_pro_single">
                      <input type="file" id="ArticlesOfIncorporation" name="articlesOfIncorporation" required>
                    </div>
                  </div>
                  <div class="col-xl-4 col-md-4">
                    <label for="Position">Position</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" id="Position" placeholder="Last Name" name="position" required value="{{old('position')}}">
                    </div>
                  </div>
                  <div class="col-xl-4 col-md-4">
                    <label for="legalEmail">Legal Representative's Email</label>
                    <div class="wsus__dash_pro_single">
                      <input type="email" id="legalEmail" placeholder="Legal Representative's Email" name="legalEmail" required value="{{old('legalEmail')}}">
                    </div>
                  </div>
                  <div class="col-xl-4 col-md-4">
                    <label for="cLegalEmail">Confirm Legal Representative's Email</label>
                    <div class="wsus__dash_pro_single">
                      <input type="email" id="cLegalEmail" placeholder="Confirm Legal Representative's Email" name="cLegalEmail" required value="{{old('cLegalEmail')}}">
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-12 d-flex justify-content-between">
                <a href="#!" class="common_btn mb-4 mt-2 prev">{{__('user.Previous')}}</a>
                <a href="#!" class="common_btn mb-4 mt-2 next">{{__('user.Next')}}</a>
                <!-- <button class="common_btn mb-4 mt-2" type="submit">{{__('user.Submit Request')}}</button> -->
              </div>

            </div>
            <!-- Step 4 -->
            <div class="row form_step d-none">
              <div class="col-xl-12">
                <div class="row">
                  <div class="col-12">
                    <div class="info">
                      <i class="fas fa-info"></i> Please choose between 1 and 5 product categories that you sell. The list of categories displayed below is generic, so please select the closest categories to your field. This step is mandatory for the continuation of the registration process.
                    </div>
                  </div>
                  @foreach ($productCategories as $key => $category)
                  <div class="col-3 mb-3">
                    <div class="check_box">
                      <input type="checkbox" class="catCheck" name="cat_check[]" value="{{$category->name}}" id="category-{{$key}}" required>
                      <label for="category-{{$key}}">{{$category->name}}</label>
                    </div>
                  </div>
                  @endforeach
                </div>
              </div>
              <div class="col-xl-12">
                <div class="terms_area">
                  <div class="form-check position-relative">
                    <input name="agree_terms_condition" class="form-check-input" type="checkbox" value="1" id="flexCheckChecked3" required>
                    <label class="form-check-label" for="flexCheckChecked3">
                      {{__('user.I have read and agree with terms and conditions')}}
                    </label>
                  </div>
                </div>
              </div>
              <div class="col-xl-12 d-flex justify-content-between">
                <a href="#!" class="common_btn mb-4 mt-2 prev">{{__('user.Previous')}}</a>
                <button class="common_btn mb-4 mt-2" type="submit">{{__('user.Submit Request')}}</button>
              </div>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
  (function($) {
    "use strict";
    $(document).ready(function() {
      $("#state_id").on("change", function() {
        var countryId = $("#state_id").val();
        if (countryId) {
          $.ajax({
            type: "get",
            url: "{{url('/user/city-by-state/')}}" + "/" + countryId,
            success: function(response) {
              $("#city_id").html(response.cities);
            },
            error: function(err) {}
          })
        } else {
          var response = "<option value=''>{{__('user.Select Locality')}}</option>";
          $("#city_id").html(response);
        }
      });

      $('input[name*="cat_check"]').change(function() {
        if ($('input[name*="cat_check"]:checked').length === 5) {
          $('input[name*="cat_check"]:not(:checked)').parent().css('pointer-events', 'none');
        } else {
          $('input[name*="cat_check"]:not(:checked)').parent().css('pointer-events', 'auto');
        }
      });
      $(document).on("click", ".next", function(e) {
        e.preventDefault();
        var ruleSetImg = {
          required: true,
          extension: "png|jpg|jpeg",
        };
        var message = {
          extension: "Allowed file types (png or jpg)",
        };
        var ruleSetPDForImg = {
          required: true,
          extension: "pdf|png|jpg|jpeg",
        };
        var message1 = {
          extension: "Allowed file types (pdf, png or jpg)",
        };
        let form = $("#multiStepsForm").validate({
          rules: {
            banner_image: ruleSetImg,
            idCardSignatory: ruleSetPDForImg,
            certificateRegistration: ruleSetPDForImg,
            bankStatement: ruleSetPDForImg,
            articlesOfIncorporation: ruleSetPDForImg,
            "cat_check[]": {
              required: true
            }
          },
          messages: {
            banner_image: message,
            idCardSignatory: message1,
            certificateRegistration: message1,
            bankStatement: message1,
            articlesOfIncorporation: message1,
            "cat_check[]": {
              required: "Please select between 1 and 5."
            }
          },
        });

        let valid = $("#multiStepsForm").valid();
        if (valid) {
          $(this)
            .parents(".form_step")
            .addClass("d-none")
            .next()
            .removeClass("d-none");
          let index = $(this).parents(".form_step").index() + 1;
          $(`#progressbar li:nth-child(${index})`)
            .addClass("active")
            .siblings()
            .removeClass("active");
        } else {}
      });

      $(document).on('click', '.prev', function(e) {
        e.preventDefault();
        $(this).parents('.form_step').addClass('d-none').prev().removeClass('d-none');
        let index = $(this).parents('.form_step').index() - 1;
        $(`#progressbar li:nth-child(${index})`).addClass('active').siblings().removeClass('active');
      });

      $(document).on('click', '[id="v-pills-tab"] a', function(e) {
        e.preventDefault();
        $(this).addClass('active').siblings().removeClass('active');
        let index = $(this).index() + 1;
        $(`[id="v-pills-tabContent"] .tab-pane:nth-child(${index})`).addClass('active show').siblings().removeClass('active show');
      });

      // $(document).on('click', '.next', function(e){
      //   e.preventDefault();


      // });

    });
  })(jQuery);
</script>

@endsection