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

/* div#privateAdsContainer {
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
} */


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
    display: grid;
    gap: 30px;
    grid-template-columns: repeat(4, 1fr);
    place-items: center;
}
.pricing-card {
    background-color: #ff6600;
    border-radius: 20px;
    color: #fff;
    text-align: center;
    /* padding: 40px; */
    position: relative;
    width: 100%;
    animation: fadeInUp 0.5s ease-in-out;
    box-shadow: 0px 5px 0px black;
    height: 350px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}
.ribbon {
    background-color: black;
    color: white;
    font-weight: bold;
    font-size: 20px;
    padding: 10px 0;
    position: absolute;
    top: 15px;
    left: 50%;
    transform: translateX(-50%);
    width: 100%;
    /* border-radius: 10px 10px 0 0; */
}

.content {
    margin-top: 50px;
}

.content p {
    color: #fff;
}

.buy-button {
    background-color: black;
    border: none;
    border-radius: 20px;
    color: white;
    cursor: pointer;
    margin-top: 20px;
    padding: 10px;
    width: 100%;
    transition: background-color 0.3s ease-in-out;
    display: flex;
    justify-content: center;
    align-items: center;
}

.buy-button span {
    font-size: 22px;
    font-weight: bold;
    color: #fff;
}

.buy-button:hover {
    background-color: #333;
}

@media screen and (max-width: 599px) {
.pricing-table {
    grid-template-columns: repeat(1, 1fr);
    width: 100%;
    place-items: center;
}

.pricing-card {
    height: 300px;
}
.content p {
    color: #fff;
    font-size: 18px;
}
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

<?php
$Ads = [
            "1" => [
                "header" => "1 Ad Posting",
                "price" => "2.99",
                "title" => "1 Ad posting on Voop",
                "content" => "Personalized Dashboard",
                "button" => "Buy"
            ],
            "30" => [
                "header" => "30 Ads Posting",
                "price" => "14.99",
                "title" => "30 Ad posting on Voop",
                "content" => "Personalized Dashboard",
                "button" => "Buy"
            ],
            "50" => [
                "header" => "50 Ads Posting",
                "price" => "24.99",
                "title" => "50 Ad posting on Voop",
                "content" => "Personalized Dashboard",
                "button" => "Buy"
            ],
            "100" => [
                "header" => "100 Ads Posting",
                "price" => "49.99",
                "title" => "100 Ad posting on Voop",
                "content" => "Personalized Dashboard",
                "button" => "Buy"
            ]
        ];

?>
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
             {{-- id="Public_Seller_Content" --}}
            <div class="col-xl-12">
              <h3 style="text-transform: uppercase">TERMENI ȘI CONDIȚII - PERSOANE JURIDICE - TradeMag</h3>
              <h4><strong>Taxă de abonament lunară - 14,99 euro.</strong></h4>
              <p style="color: #000" class="mb-4">
                Aceasta oferă posibilitatea de a posta anunțuri nelimitate pentru o perioadă de o lună de zile, site-ul
                nostru acționând ca propriul dumneavoastră magazin de vânzări produse.
              </p>
              <h3 style="text-transform: uppercase">TERMENI ȘI CONDIȚII - PERSOANE FIZICE – VOOP</h3>
              <strong class="mb-3">Prețuri:</strong>
              <ul>
                 <h4><strong>1 Anunț - 2,99 Euro</strong></h4>
              </ul>
               <ul>
                    <h4><strong>30 Anunțuri - 14,99 Euro</strong></h4>
               </ul>

               <ul>
                   <h4><strong>50 Anunțuri - 24,99 Euro</strong></h4>
               </ul>

               <ul>
                   <h4><strong>100 Anunțuri - 49,99 Euro</strong></h4>
               </ul>

               <p style="color: #000">Prețul reprezintă tariful pentru publicarea anunțurilor timp de 60 de zile (2 luni) pe site-ul nostru,
oferindu-vă astfel posibilitatea de a expune și vinde produsul pe o perioadă mai îndelungată.</p>

<p style="color: #000;  font-style: italic;">
  *Nu aprobăm vânzarea produselor ilegale.
  <br>
  TradeMag și Voop sunt platforme de prezentare a produselor și realizează legătura între
  vânzători și cumpărători.
  <br>
  Pentru publicitate în orice alt spațiu și/sau durată ne puteți contacta la publicitate@trademag.ro.
</p>

            </div>
            {{-- <div class="col-xl-12 mb-3" id="Private_Seller_Content" style="display: none">
              <h3>TERMENI ȘI CONDIȚII - PERSOANE FIZICE – VOOP</h3>
              <strong class="mb-3">Prețuri:</strong>
              <ul>
                <li>1 Anunț - <strong>2,99 </strong> Euro</li>
              </ul>
               <ul>
                    <li>30 Anunțuri - <strong>14,99</strong> Euro</li>
               </ul>

               <ul>
                    <li>50 Anunțuri - <strong>24,99</strong> Euro</li>
               </ul>

               <ul>
                    <li>100 Anunțuri - <strong>49,99</strong> Euro</li>
               </ul>

               <p>Prețul reprezintă tariful pentru publicarea anunțurilor timp de 60 de zile (2 luni) pe site-ul nostru,
oferindu-vă astfel posibilitatea de a expune și vinde produsul pe o perioadă mai îndelungată.</p>

<strong>*Nu aprobăm vânzarea produselor ilegale.</strong>

<p class="mb-4">TradeMag   și   Voop   sunt   platforme   de   prezentare   a   produselor   și   realizează   legătura   între
vânzători și cumpărători.</p>
Pentru publicitate în orice alt spațiu și/sau durată ne puteți contacta la <strong>publicitate@trademag.ro</strong>.
            </div> --}}
<div class="col-12  col-md-5">
                    <label for="Seller_Method" class="Seller_Method">Slelect Your Seller Type</label>
                    <div class="wsus__dash_pro_single gap-3">
                      <div class="seller-1">
                        <input type="radio" id="seller" name="seller" value="Public" required checked>
                        <label for="seller" title="PERSOANE JURIDICE"></label>
                      </div>
                        {{-- <div class="seller-2">
                            <label for="public/private" title="Public/Private"></label>
                            <input type="radio" id="sellerboth" name="seller" value="both" required>
                        </div> --}}
                        <div class="seller-3">
                          <input type="radio" id="privateseller" name="seller"  value="Private" required>
                         <label for="privateseller" title="PERSOANE FIZICE"></label>
                      </div>
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
            @foreach ($Ads as $key => $Ad)
        <div class="pricing-card">
            <div class="ribbon">{{ $Ad['header'] }}</div>
            <div class="content">
                <p>{{ $Ad['title'] }}</p>
                <p>{{ $Ad['content'] }}</p>
                <p>{{ $Ad['price'] }} Euros</p>

                <button data-private-ads="{{ $key }}" class="buy-button">
                        <div id="loader" class="loader" style="display:none;"></div>
                <span data-text="{{ $Ad['button'] }}">{{ $Ad['button'] }}</span>
                </button>
            </div>
        </div>
            @endforeach
        </div>
        </div>


        </div>

        {{-- Buttons  --}}
         {{-- <div class="col-xl-12 col-md-12"> --}}
                          {{-- <a class="common_btn" href="https://buy.stripe.com/test_bIY3fta3L1i79QQ4gi">Pay with Stripe</a> --}}
                 {{-- <a class="common_btn" id="Seller_Type_btn" href="#!" >
                    Pay with Stripe
                    <div id="loader" class="loader" style="display:none;"></div>
                </a>
                    </div> --}}

                {{-- </div> --}}

                <button id="Seller_Type_btn" type="submit" class="common_btn mb-4 mt-2 next">
         <div id="loader" class="loader" style="display:none;"></div>
                                <span class="text-white">Pay with Stripe</span>
                            </button>
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

   if (value == "Public") {
      $('#privateAdsContainer').hide();
       $('#Seller_Type_btn').show();
       $('#Private_Seller_Content').hide()
       $('#Public_Seller_Content').show()
    } else {
        $('#privateAdsContainer').show();
      $('#Seller_Type_btn').hide();
      $('#Private_Seller_Content').show()
      $('#Public_Seller_Content').hide()
    }
      });

//        $('select[name="private_ads"]').change(function (e) {
//         e.preventDefault();
//     private_ads = e.target.value;
//     updatePricingInfo(private_ads);
//   });


  const handleButtonRequest = (button) => {
          button.addEventListener("click", () => {
            // private_ads = button.getAttribute("data-private-ads");
            private_ads = $(button).data("private-ads");
            const text_data = $('.buy-button span').data('text');
           let requestData = { value: value };
            if (value === "Private") {
                requestData.privateAds = private_ads;
            }

        $.ajax({
                type: "get",
                url: "/user/stripe-payment",
                data: requestData,
                  beforeSend: function(){
                button.querySelector('span').textContent = "";
                let loader = document.createElement('div');
                loader.className = 'loader';
                button.appendChild(loader);
                },
                success: function (response) {
                  button.querySelector('span').textContent = text_data;
                let loader = button.querySelector('.loader');
                if (loader) {
                    button.removeChild(loader);
                }
                     window.location.href = response;
                }
            });
      });
    }

//   for new card work
 const buyButtons = document.querySelectorAll(".buy-button");
    buyButtons.forEach(button => {
         handleButtonRequest(button)
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



// Main Ajax Request
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
