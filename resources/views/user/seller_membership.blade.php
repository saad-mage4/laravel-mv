@extends('user.layout')
@section('title')
<title>{{__('user.Become a Seller')}}</title>
@endsection
@section('user-content')
<link rel="stylesheet" href="{{ asset('user/css/seller_reg.css') }}">
<style>
#list-styled ul {
    list-style: initial;
    padding-left: 30px;
}
#list-styled ul li {
    margin-bottom: 10px;
}
</style>
<div class="row">
  <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
    <div class="dashboard_content mt-2 mt-md-0">
      <h3><i class="far fa-user"></i> {{__('user.Membership')}}</h3>
      <div class="wsus__dashboard_profile mb-4 d-none">
        <div class="wsus__dash_pro_area">
          {!! $setting->seller_condition !!}
        </div>
      </div>

      <div class="wsus__dashboard_profile">
        <div class="wsus__dash_pro_area">
          <div class="row mb-3" id="list-styled">
            <div class="col-xl-12">
              <h3>TERMS & CONDITION</h3>
              <strong>One Time Fee:</strong>
              <ul>
                <li>Upon registration, a one-time fee of <strong>14.99</strong> Euros will be charged to activate your seller account.</li>
              </ul>
              <div class="d-none">
              <strong>Monthly Membership Fee:</strong>
              <ul>
                <li>In addition to the initial fee, a monthly membership fee will be charged based on your sales volume. Here's how the monthly membership fee is calculated:
                  <ul>
                    <li>
                      Sales up to <strong>1000</strong> Euros: Monthly fee of 15 Euros
                    </li>
                    <li>
                      Sales from <strong>1001 to 5000</strong> Euros: Monthly fee of 50 Euros
                    </li>
                    <li>
                      Sales from <strong>5001 to 10000</strong> Euros: Monthly fee of 100 Euros
                    </li>
                    <li>
                      Please note that the monthly membership fee will be automatically deducted from your account balance on a monthly basis, based on your sales volume from the previous month.
                    </li>
                  </ul>
                </li>
              </ul>
              </div>
            </div>
{{--              @php(dd(\Illuminate\Support\Facades\Auth::id()))--}}
              <div class="col-xl-4">
                  <form action="{{ route('user.membership.subscribe') }}" method="post">
                      @csrf
                                  <a href="https://buy.stripe.com/test_bIY3fta3L1i79QQ4gi">Pay with Stripe</a>
                  </form>

              </div>
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
