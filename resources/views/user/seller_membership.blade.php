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

div#privateAdsContainer {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 14px;
}

#privateAdsContainer label {
    white-space: nowrap
}

.price {
  font-weight: bold;
  color: #18587a;
}


/* price Adds */
/* body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
} */

.pricing-table {
    display: flex;
    gap: 20px;
}

.pricing-card {
    background-color: #ff6600;
    border-radius: 10px;
    color: white;
    text-align: center;
    padding: 20px;
    position: relative;
    width: 200px;
    animation: fadeInUp 0.5s ease-in-out;
}

.ribbon {
    background-color: black;
    color: white;
    font-weight: bold;
    padding: 5px 0;
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100%;
    border-radius: 10px 10px 0 0;
}

.content {
    margin-top: 30px;
}

.buy-button {
    background-color: black;
    border: none;
    border-radius: 5px;
    color: white;
    cursor: pointer;
    margin-top: 20px;
    padding: 10px;
    width: 100%;
    transition: background-color 0.3s ease-in-out;
}

.buy-button:hover {
    background-color: #333;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
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
              <strong>Monthly Subscription Fee:</strong>
              <ul>
                <li>By subscribing to our service, you agree to pay a monthly fee of <strong>14.99</strong> Euros.</li>
              </ul>
               <ul>
                    <li>In addition to the subscription fee, a <strong>1%</strong> commission per withdrawal will be applied.</li>
               </ul>
                <strong>Return Policy:</strong>
               <ul>
                    <li>The Seller will reimburse the amount within <strong>14</strong> days from the Buyer's decision to withdraw.</li>
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
            <div class="col-xl-4 col-md-6">
                    <label for="Seller_Method" class="Seller_Method">Slelect Your Seller Type</label>
                    <div class="wsus__dash_pro_single gap-3">
                      <div class="seller-1">
                        <label for="seller" title="Public"></label>
                        <input type="radio" id="seller" name="seller" value="Public" required checked>
                      </div>
                        {{-- <div class="seller-2">
                            <label for="public/private" title="Public/Private"></label>
                            <input type="radio" id="sellerboth" name="seller" value="both" required>
                        </div> --}}
                       <div class="seller-3">
                        <label for="privateseller" title="Private"></label>
                        <input type="radio" id="privateseller" name="seller"  value="Private" required>
                      </div>


                    </div>

                      <div id="privateAdsContainer" style="display: none;">
                        {{-- <label for="private_ads" title="Seller Ads">Seller Ads</label>
                    <select name="private_ads">
                    <option value="1">1</option>
                    <option value="30">30</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    </select>
                     <p id="pricingInfo"></p> --}}
                     <div class="pricing-table">
        <div class="pricing-card">
            <div class="ribbon">1 Ad Posting</div>
            <div class="content">
                <p>1 Ad posting on Voop</p>
                <p>Personalized Dashboard</p>
                <p>4.99 Euros</p>
                <button class="buy-button">Buy</button>
            </div>
        </div>
        <div class="pricing-card">
            <div class="ribbon">30 Ads Posting</div>
            <div class="content">
                <p>30 Ad postings on Voop</p>
                <p>Personalized Dashboard</p>
                <p>14.99 Euros</p>
                <button class="buy-button">Buy</button>
            </div>
        </div>
        <div class="pricing-card">
            <div class="ribbon">50 Ads Posting</div>
            <div class="content">
                <p>50 Ad postings on Voop</p>
                <p>Personalized Dashboard</p>
                <p>24.99 Euros</p>
                <button class="buy-button">Buy</button>
            </div>
        </div>
        <div class="pricing-card">
            <div class="ribbon">100 Ads Posting</div>
            <div class="content">
                <p>100 Ads posting on Voop</p>
                <p>Personalized Dashboard</p>
                <p>49.99 Euros</p>
                <button class="buy-button">Buy</button>
            </div>
        </div>
    </div>
                    </div>

                    <div class="col-xl-8 col-md-6">

                          {{-- <a class="common_btn" href="https://buy.stripe.com/test_bIY3fta3L1i79QQ4gi">Pay with Stripe</a> --}}
                 {{-- <a class="common_btn" id="Seller_Type_btn" href="#!" >
                    Pay with Stripe
                    <div id="loader" class="loader" style="display:none;"></div>
                </a>
                    </div> --}}

                </div>
                <button id="Seller_Type_btn" type="submit" class="common_btn mb-4 mt-2 next">
         <div id="loader" class="loader" style="display:none;"></div>
                                <span class="text-white">Pay with Stripe</span>
                            </button>
{{--              @php(dd(\Illuminate\Support\Facades\Auth::id()))--}}
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

    //   For Stripe Seller
      let value = "Public";
      let private_ads = "1";

      $('input[name="seller"]').change(function (e) {
        e.preventDefault();
        value  = e?.target?.value;

         if (value === "Private") {
      $('#privateAdsContainer').show();
    } else {
      $('#privateAdsContainer').hide();
    }
      });

       $('select[name="private_ads"]').change(function (e) {
        e.preventDefault();
    private_ads = e.target.value;
    updatePricingInfo(private_ads);
  });


  function updatePricingInfo(private_ads) {
    let pricingInfo = "";

   switch (private_ads) {
      case "1":
        pricingInfo = '1 ad/product posting - <span class="price">5 Euro</span>';
        break;
      case "30":
        pricingInfo = '30 ads/product postings - <span class="price">14.99 Euro</span>';
        break;
      case "50":
        pricingInfo = '50 ads/product postings - <span class="price">25 Euro</span>';
        break;
      case "100":
        pricingInfo = '100 ads/product postings - <span class="price">50 Euro</span>';
        break;
    }

    $('#pricingInfo').html(pricingInfo);
  }

updatePricingInfo(private_ads);



      $('#Seller_Type_btn').click(function (e) {
        e.preventDefault();

        let requestData = { value: value };
    if (value === "Private") {
      requestData.privateAds = private_ads;
    }

        $.ajax({
                type: "get",
                url: "/user/stripe-payment",
                // data:{
                //     value: value,
                //     privateAds: private_ads
                // },
                data: requestData,
                  beforeSend: function(){
                  $('#Seller_Type_btn span').text("");
                  $('#loader').show();
                },
                success: function (response) {
                  $('#Seller_Type_btn span').text("Pay with Stripe");
                  $('#loader').hide();
                     window.location.href = response;
                }
            });
      });
    });
  })(jQuery);
</script>

@endsection
