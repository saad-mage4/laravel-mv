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
                  <li id="confirm"><strong>Operational Information</strong></li>
                </ul>
              </div>
            </div>
          <form action="{{ route('user.seller-request') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Step 1 -->
            <div class="row form_step">
              <div class="col-xl-12">
                <div class="row">
                  <div class="col-xl-6 col-md-6">
                    <label for="FirstName">First Name</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" id="FirstName" placeholder="First Name" name="firstName">
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="LastName">Last Name</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" id="LastName" placeholder="Last Name" name="lastName">
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="phoneNumber">Phone Number</label>
                    <div class="wsus__dash_pro_single">
                      <input type="tel" id="phoneNumber" placeholder="Phone Number" name="phoneNumber">
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="email">Email</label>
                    <div class="wsus__dash_pro_single">
                      <input type="email" id="email" placeholder="Email" name="email">
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="address">Address</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" id="address" placeholder="Address" name="address">
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="postal-code">Postal Code</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" id="postal-code" placeholder="Postal Code" name="postalCode">
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="country">Company headquarters country location</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" id="country" name="country" class="form-control" value="Romania" required readonly>
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="company-name">Company name</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" id="company-name" placeholder="Company name" name="companyName">
                    </div>
                  </div>

                  <div class="col-xl-6 col-md-6">
                    <label for="company-type">Company Type</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" id="company-type" placeholder="Company Type" name="companyType">
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="urc">Unique Registration Code</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" id="urc" placeholder="Unique Registration Code" name="urc">
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="VAT-payer">VAT payer</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" id="VAT-payer" placeholder="VAT payer" name="vat">
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
                      <input type="text" placeholder="IBAN" name="iban">
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="Bank">Bank</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" placeholder="Bank" name="bank">
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="SWIFT">SWIFT</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" placeholder="SWIFT" name="swift">
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="Local-currency">Local currency (RON)</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" placeholder="Local-currency" name="localCurrency">
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="producer-label">I am a romanian producer?</label>
                    <div class="wsus__dash_pro_single gap-3">
                      <div class="radio-1">
                        <label for="producer-1"></label>
                        <input type="radio" id="producer-1" name="producer">
                      </div>
                      <div class="radio-2">
                        <label for="producer-2"></label>
                        <input type="radio" id="producer-2" name="producer">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <label for="company-desc">Brief or short company description</label>
                    <div class="wsus__dash_pro_single">
                      <textarea type="text" id="company-desc" placeholder="Brief or short company description" name="companyDesc"></textarea>
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
                      <input type="file" id="certificateRegistration" name="certificateRegistration">
                    </div>
                  </div>
                  <div class="col-12 col-lg-3">
                    <label for="idCardSignatory">ID Card of Signatory</label>
                    <div class="wsus__dash_pro_single">
                      <input type="file" id="idCardSignatory" name="idCardSignatory">
                    </div>
                  </div>
                  <div class="col-12 col-lg-3">
                    <label for="BankStatement">Bank Statement</label>
                    <div class="wsus__dash_pro_single">
                      <input type="file" id="BankStatement" name="bankStatement">
                    </div>
                  </div>
                  <div class="col-12 col-lg-3">
                    <label for="ArticlesOfIncorporation">Articles of Incorporation</label>
                    <div class="wsus__dash_pro_single">
                      <input type="file" id="ArticlesOfIncorporation" name="articlesOfIncorporation">
                    </div>
                  </div>
                  <div class="col-xl-4 col-md-4">
                    <label for="FirstName1">First Name</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" id="FirstName1" placeholder="First Name" name="firstName1">
                    </div>
                  </div>
                  <div class="col-xl-4 col-md-4">
                    <label for="LastName1">Last Name</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" id="LastName1" placeholder="Last Name" name="lastName1">
                    </div>
                  </div>
                  <div class="col-xl-4 col-md-4">
                    <label for="Position">Position</label>
                    <div class="wsus__dash_pro_single">
                      <input type="text" id="Position" placeholder="Last Name" name="position">
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="legalEmail">Legal Representative's Email</label>
                    <div class="wsus__dash_pro_single">
                      <input type="email" id="legalEmail" placeholder="Legal Representative's Email" name="legalEmail">
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <label for="cLegalEmail">Confirm Legal Representative's Email</label>
                    <div class="wsus__dash_pro_single">
                      <input type="email" id="cLegalEmail" placeholder="Confirm Legal Representative's Email" name="cLegalEmail">
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
                  <div class="col-md-3">
                    <!-- Tabs nav -->
                    <div class="nav flex-column nav-pills nav-pills-custom" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                      <a class="nav-link mb-3 p-3 shadow active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">
                        <span class="font-weight-bold small text-uppercase">Personal information</span></a>

                      <a class="nav-link mb-3 p-3 shadow" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">
                        <span class="font-weight-bold small text-uppercase">Bookings</span></a>

                      <a class="nav-link mb-3 p-3 shadow" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">
                        <span class="font-weight-bold small text-uppercase">Reviews</span></a>

                      <a class="nav-link mb-3 p-3 shadow" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">
                        <span class="font-weight-bold small text-uppercase">Confirm booking</span></a>
                    </div>
                  </div>

                  <div class="col-md-9 mb-4">
                    <!-- Tabs content -->
                    <div class="tab-content" id="v-pills-tabContent">
                      <div class="tab-pane fade shadow rounded bg-white show active p-5" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                        <h4 class="font-italic mb-4">
                          Personal information
                        </h4>
                        <div class="range-checkbox-main">
                          <div class="check-item">
                            <input type="checkbox" name="cat-check" id="check" value="Other Items">
                            <label for="check">Other Items</label>
                          </div>
                          <div class="check-item">
                            <input type="checkbox" name="cat-check" id="check1" value="SDA Others">
                            <label for="check1">SDA Others</label>
                          </div>
                          <div class="check-item">
                            <input type="checkbox" name="cat-check" id="check2" value="Other Photos">
                            <label for="check2">Other Photos</label>
                          </div>
                          <div class="check-item">
                            <input type="checkbox" name="cat-check" id="check3" value="Camera, Videos &amp; Accessories">
                            <label for="check3">Camera, Videos &amp; Accessories</label>
                          </div>
                          <div class="check-item">
                            <input type="checkbox" name="cat-check" id="check4" value="Gaming Hardware">
                            <label for="check4">Gaming Hardware</label>
                          </div>
                          <div class="check-item">
                            <input type="checkbox" name="cat-check" id="check5" value="MDA Others">
                            <label for="check5">MDA Others</label>
                          </div>
                          <div class="check-item">
                            <input type="checkbox" name="cat-check" id="check6" value="AC Others">
                            <label for="check6">AC Others</label>
                          </div>
                          <div class="check-item">
                            <input type="checkbox" name="cat-check" id="check7" value="">
                            <label for="check7">TV ACC</label>
                          </div>
                          <div class="check-item">
                            <input type="checkbox" name="cat-check" id="check8" value="Audo Video &amp; Hifi">
                            <label for="check8">Audo Video &amp; Hifi</label>
                          </div>
                          <div class="check-item">
                            <input type="checkbox" name="cat-check" id="check9" value="Aparate Photo &amp; DSLR">
                            <label for="check9">Aparate Photo &amp; DSLR</label>
                          </div>
                        </div>
                      </div>

                      <div class="tab-pane fade shadow rounded bg-white p-5" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                        <h4 class="font-italic mb-4">Bookings</h4>
                        <p class="font-italic text-muted mb-2">
                          Lorem ipsum dolor sit amet, consectetur
                          adipisicing elit, sed do eiusmod tempor
                          incididunt ut labore et dolore magna aliqua.
                          Ut enim ad minim veniam, quis nostrud
                          exercitation ullamco laboris nisi ut aliquip
                          ex ea commodo consequat. Duis aute irure dolor
                          in reprehenderit in voluptate velit esse
                          cillum dolore eu fugiat nulla pariatur.
                          Excepteur sint occaecat cupidatat non
                          proident, sunt in culpa qui officia deserunt
                          mollit anim id est laborum.
                        </p>
                      </div>

                      <div class="tab-pane fade shadow rounded bg-white p-5" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                        <h4 class="font-italic mb-4">Reviews</h4>
                        <p class="font-italic text-muted mb-2">
                          Lorem ipsum dolor sit amet, consectetur
                          adipisicing elit, sed do eiusmod tempor
                          incididunt ut labore et dolore magna aliqua.
                          Ut enim ad minim veniam, quis nostrud
                          exercitation ullamco laboris nisi ut aliquip
                          ex ea commodo consequat. Duis aute irure dolor
                          in reprehenderit in voluptate velit esse
                          cillum dolore eu fugiat nulla pariatur.
                          Excepteur sint occaecat cupidatat non
                          proident, sunt in culpa qui officia deserunt
                          mollit anim id est laborum.
                        </p>
                      </div>

                      <div class="tab-pane fade shadow rounded bg-white p-5" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                        <h4 class="font-italic mb-4">
                          Confirm booking
                        </h4>
                        <p class="font-italic text-muted mb-2">
                          Lorem ipsum dolor sit amet, consectetur
                          adipisicing elit, sed do eiusmod tempor
                          incididunt ut labore et dolore magna aliqua.
                          Ut enim ad minim veniam, quis nostrud
                          exercitation ullamco laboris nisi ut aliquip
                          ex ea commodo consequat. Duis aute irure dolor
                          in reprehenderit in voluptate velit esse
                          cillum dolore eu fugiat nulla pariatur.
                          Excepteur sint occaecat cupidatat non
                          proident, sunt in culpa qui officia deserunt
                          mollit anim id est laborum.
                        </p>
                      </div>
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
            <!-- Step 5 -->
            <div class="row form_step d-none">
              <div class="col-xl-12">
                <div class="row">
                  <div class="col-xl-4 col-md-4">
                    <label for="producer-label">Return Period</label>
                    <div class="wsus__dash_pro_single gap-3 flex-wrap">
                      <div class="radio-1">
                        <label class="label-period" for="period-1" title="14 Days"></label>
                        <input type="radio" id="period-1" name="period">
                      </div>
                      <div class="radio-2">
                        <label class="label-period" for="period-2" title="30 Days"></label>
                        <input type="radio" id="period-2" name="period">
                      </div>
                      <div class="radio-1">
                        <label class="label-period" for="period-3" title="60 Days"></label>
                        <input type="radio" id="period-3" name="period">
                      </div>
                      <div class="radio-2">
                        <label class="label-period" for="period-4" title="90 Days"></label>
                        <input type="radio" id="period-4" name="period">
                      </div>
                    </div>
                  </div>             
                  <div class="col-xl-4 col-md-4">
                    <label for="maxOrderTime">Maximum Order Processing Time</label>
                    <div class="wsus__dash_pro_single">
                      <input type="time" id="maxOrderTime" name="maxOrderTime">
                    </div>
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
  @endsection