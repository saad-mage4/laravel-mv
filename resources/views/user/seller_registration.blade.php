@extends('user.layout')
@section('title')
<title>{{__('user.Become a Seller')}}</title>
@endsection
@section('user-content')
<style>

body {
  counter-reset: my-counter;
}

#heading {
    text-transform: uppercase;
    color: #FD5201;
    font-weight: normal
}

#msform {
    text-align: center;
    position: relative;
    margin-top: 20px
}
.ms-form-main .container{
    max-width: 1320px !important;
}

#msform fieldset {
    background: white;
    border: 0 none;
    border-radius: 0.5rem;
    box-sizing: border-box;
    width: 100%;
    margin: 0;
    padding-bottom: 20px;
    position: relative
}

.form-card {
    text-align: left
}

#msform fieldset:not(:first-of-type) {
    display: none
}

#msform input, #msform textarea, #msform select {
    padding: 8px 15px 8px 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-bottom: 25px;
    margin-top: 2px;
    width: 100%;
    box-sizing: border-box;
    font-family: montserrat;
    color: #000;
    background-color: transparent;
    font-size: 16px;
    letter-spacing: 1px;
    height: 45px;
    transition: all 0.3s ease;
}

#msform input:focus,
#msform textarea:focus {
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    border: 1px solid #FD5201;
    outline-width: 0
}

#msform .action-button {
    width: 100px;
    background: #FD5201;
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 0px;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 0px 10px 5px;
    float: right;
    text-transform: uppercase;
}

#msform .action-button:hover,
#msform .action-button:focus {
    background-color: #18587A
}

#msform .action-button-previous {
    width: 100px;
    background: #616161;
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 0px;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 5px 10px 0px;
    float: right
}

#msform .action-button-previous:hover,
#msform .action-button-previous:focus {
    background-color: #000000
}

.card {
    z-index: 0;
    border: none;
    position: relative
}

.fs-title {
    font-size: 25px;
    color: #FD5201;
    margin-bottom: 30px;
    font-weight: normal;
    text-align: left;
    text-decoration: underline;
}

.purple-text {
    color: #FD5201;
    font-weight: normal
}

.steps {
    font-size: 25px;
    color: gray;
    margin-bottom: 10px;
    font-weight: normal;
    text-align: right
}

.fieldlabels {
    color: #000;
    text-align: left;
    font-weight: 700;
}
.fieldlabels span.required {
    color: red;
    text-align: left;
    font-weight: 700;
}

#progressbar {
    margin-bottom: 30px;
    overflow: hidden;
    color: lightgrey;
    display: flex;
    /* gap: 50px; */
    justify-content: space-between;
}

#progressbar .active {
    color: #FD5201
}

#progressbar li {
    list-style-type: none;
    font-size: 15px;
    /* width: 25%; */
    float: left;
    position: relative;
    font-weight: 400
}


#progressbar li:before {
    width: 50px;
    height: 50px;
    line-height: 45px;
    display: block;
    font-size: 20px;
    color: #18587A;
    background: lightgray;
    border-radius: 50%;
    margin: 0 auto 10px auto;
    padding: 2px;
    counter-increment: my-counter;
    content: counter(my-counter);
}

#progressbar li:after {
    content: '';
    width: 100%;
    height: 2px;
    background: lightgray;
    position: absolute;
    left: 0;
    top: 25px;
    z-index: -1
}

#progressbar li.active:before,
#progressbar li.active:after {
    background: #FD5201
}

.progress {
    height: 20px
}

.progress-bar {
    background-color: #FD5201
}

.fit-image {
    width: 100%;
    object-fit: cover
}



.info {
    padding: 20px;
    background-color: #ececec;
    border: 2px solid #dddddd;
    margin-bottom: 40px;
    border-radius: 8px;
    display: flex;
    gap: 13px;
    align-items: flex-start;
}
.info i {
    color: #18587A;
    font-size: 24px;
}



.radio-main {
    display: flex;
    gap: 30px;
    margin-bottom: 50px;
    margin-top: 20px;
}
.radio-main .radio-btn {
    display: flex;
    flex-direction: row-reverse;
    align-items: center;
    gap: 20px;
    /* padding: 10px 30px; */
    border: 2px solid #18587A;
    width: 150px;
    align-items: center;
    justify-content: flex-end;
    padding-left: 20px;
    height: 40px;
    border-radius: 8px;
}
.radio-main .radio-btn *{
   margin: 0 !important;
}

.radio-main .radio-btn input{
    width: unset !important;
    padding: 0 !important;
    height: unset !important;
    width: unset !important;
}




div#v-pills-tab {
    gap: 12px;
  }

  .nav-pills-custom .nav-link {
    color: #aaa;
    background: #fff;
    position: relative;
    margin-bottom: 0 !important;
    border: 2px solid;
    border-radius: 0;
    box-shadow: none !important;
    padding: 10px 20px !important;
  }

  .nav-pills-custom .nav-link.active {
    color: #fd5201;
    background: #fff;
    border: 2px solid #fd5201;
  }

  /* Add indicator arrow for the active tab */
  @media (min-width: 992px) {
    .nav-pills-custom .nav-link::before {
      content: "";
      display: block;
      border-top: 8px solid transparent;
      border-left: 10px solid #fd5201;
      border-bottom: 8px solid transparent;
      position: absolute;
      top: 50%;
      right: -10px;
      transform: translateY(-50%);
      opacity: 0;
    }
  }

  .nav-pills-custom .nav-link.active::before {
    opacity: 1;
  }

  .range-checkbox-main {
    columns: 2;
  }
  .range-checkbox-main .check-item {
    display: flex;
    gap: 10px;
    align-items: center;
    margin-bottom: 15px;
  }

  .range-checkbox-main .check-item input {
    width: unset !important;
    height: unset !important;
    margin: 0 !important;
}


  .range-checkbox-main .check-item label {
    margin: 0;
    color: #aaa;
  }

  a.download-link{
    margin-top: 15px;
    color: #18587A;
    text-decoration: underline;
    text-transform: capitalize;
  }



  .last-radio-warp{
    flex-wrap: wrap;
  }
  .last-radio-btn{
    width: 33% !important;
  }

  .note-input-text{
    font-size: 16px;
    color: #aaa;
    font-style: italic;
    margin-top: -20px;
  }
</style>
<div class="row">
  <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
    <div class="dashboard_content mt-2 mt-md-0">
      <h3><i class="far fa-user"></i> {{__('user.Become a Seller')}}</h3>
      <div class="wsus__dashboard_profile mb-4">
        <div class="wsus__dash_pro_area">
          {!! $setting->seller_condition !!}
        </div>
      </div>

      <div class="wsus__dashboard_profile d-none">
        <div class="wsus__dash_pro_area">
          <form action="{{ route('user.seller-request') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-xl-12">
                <div class="row">
                  <div class="col-12">
                    <label for="">{{__('user.Banner Image')}}</label>
                    <div class="wsus__dash_pro_single">
                      <input type="file" name="banner_image">
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <div class="wsus__dash_pro_single">
                      <input type="text" placeholder="{{__('user.Shop Name')}}" name="shop_name">
                    </div>
                  </div>

                  <div class="col-xl-6 col-md-6">
                    <div class="wsus__dash_pro_single">
                      <input type="email" placeholder="{{__('user.Email')}}" name="email">
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <div class="wsus__dash_pro_single">
                      <input type="text" placeholder="{{__('user.Phone')}}" name="phone">
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <div class="wsus__dash_pro_single">
                      <input type="text" placeholder="{{ __('user.Address')}}" name="address">
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <div class="wsus__dash_pro_single">
                      <input type="text" placeholder="{{__('user.Opens at')}}" name="open_at" class="clockpicker" data-align="top" data-autoclose="true" autocomplete="off">
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <div class="wsus__dash_pro_single">
                      <input type="text" placeholder="{{__('user.Closed at')}}" name="closed_at" class="clockpicker" data-align="top" data-autoclose="true" autocomplete="off">
                    </div>
                  </div>


                  <div class="col-xl-12">
                    <div class="wsus__dash_pro_single">
                      <textarea cols="3" rows="5" name="about" placeholder="{{__('user.About You')}}"></textarea>
                    </div>
                  </div>

                  <div class="col-12">
                    <h3>Important Documents</h3>
                  </div>
                  <div class="col-12 col-lg-4">
                    <label for="">{{__('user.NIC Front')}}</label>
                    <div class="wsus__dash_pro_single">
                      <input type="file" name="nic_front_image">
                    </div>
                  </div>
                  <div class="col-12 col-lg-4">
                    <label for="">{{__('user.NIC Back')}}</label>
                    <div class="wsus__dash_pro_single">
                      <input type="file" name="nic_back_image">
                    </div>
                  </div>
                  <div class="col-12 col-lg-4">
                    <label for="">{{__('user.PDF Document')}}</label>
                    <div class="wsus__dash_pro_single">
                      <input type="file" name="pdf">
                    </div>
                  </div>

                  <div class="col-xl-12">
                    <div class="terms_area">
                      <div class="form-check">
                        <input required name="agree_terms_condition" class="form-check-input" type="checkbox" value="1" id="flexCheckChecked3">
                        <label class="form-check-label" for="flexCheckChecked3">
                          {{__('user.I have read and agree with terms and conditions')}}
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-12">
                <button class="common_btn mb-4 mt-2" type="submit">{{__('user.Submit Request')}}</button>
              </div>

            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>




<!-- Abdul Basit Work -->

<div class="ms-form-main">
      <div class="container">
        <div class="row justify-content-center">
          <div
            class="col-xl-9 col-xxl-10 col-lg-9 ms-auto"
          >
            <div class="card px-0 pt-4 pb-0 mt-3 mb-3">
              <h2 id="heading">Sign Up Your User Account</h2>
              <p>Fill all form field to go to next step</p>
              <form id="msform">
                <!-- progressbar -->
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
                <!-- <div class="progress">
                        <div
                          class="progress-bar progress-bar-striped progress-bar-animated"
                          role="progressbar"
                          aria-valuemin="0"
                          aria-valuemax="100"
                        ></div>
                      </div> -->
                <br />
                <!-- fieldsets -->
                <fieldset>
                  <div class="form-card">
                    <!-- <div class="row">
                      <div class="col-7">
                        <h2 class="fs-title">Account Information:</h2>
                      </div>
                      <div class="col-5">
                        <h2 class="steps">Step 1 - 4</h2>
                      </div>
                    </div> -->
                    <div class="row">
                      <div class="col-sm-12 col-md-6 col-lg-6">
                        <label class="fieldlabels"
                          >Company headquarters country location
                          <span class="required">*</span></label
                        >
                        <input
                          type="text"
                          name="country location"
                          placeholder="Romania"
                          readonly
                        />
                        <label class="fieldlabels"
                          >Comapany name <span class="required">*</span></label
                        >
                        <input
                          type="text"
                          name="Comapany Name"
                          placeholder="Comapany Name"
                        />
                        <label class="fieldlabels"
                          >Comapany Type <span class="required">*</span></label
                        >
                        <select name="" id="">
                          <option value="">Select Comapany Type</option>
                          <option value="">SRL</option>
                          <option value="">SA</option>
                          <option value="">SNC</option>
                          <option value="">SCS</option>
                          <option value="">SRL-D</option>
                          <option value="">PFA</option>
                          <option value="">IF</option>
                          <option value="">II</option>
                          <option value="">Other</option>
                        </select>
                        <label class="fieldlabels"
                          >Unique Registration Code
                          <span class="required">*</span></label
                        >
                        <input
                          type="text"
                          name="Code"
                          placeholder="Unique Registration Code"
                        />
                        <label class="fieldlabels"
                          >VAT payer <span class="required">*</span></label
                        >
                        <input
                          type="text"
                          name="VAT payer"
                          placeholder="VAT payer"
                        />
                      </div>
                      <div class="col-sm-12 col-md-6 col-lg-6">
                        <label class="fieldlabels"
                          >County <span class="required">*</span></label
                        >
                        <input type="text" name="County" placeholder="County" />
                        <label class="fieldlabels"
                          >Locality <span class="required">*</span></label
                        >
                        <input
                          type="text"
                          name="Locality"
                          placeholder="Locality"
                        />
                        <label class="fieldlabels"
                          >Address <span class="required">*</span></label
                        >
                        <input
                          type="text"
                          name="Address"
                          placeholder="Address"
                        />
                        <label class="fieldlabels"
                          >Postal code <span class="required">*</span></label
                        >
                        <input
                          type="number"
                          name="Postal code"
                          placeholder="Postal code"
                        />
                        <label class="fieldlabels"
                          >First Name <span class="required">*</span></label
                        >
                        <input
                          type="text"
                          name="First Name"
                          placeholder="First Name"
                        />
                        <label class="fieldlabels"
                          >Last Name <span class="required">*</span></label
                        >
                        <input
                          type="text"
                          name="First Name"
                          placeholder="First Name"
                        />
                        <label class="fieldlabels"
                          >Phone Number <span class="required">*</span></label
                        >
                        <input
                          type="number"
                          name="Phone Number"
                          placeholder="Phone Number"
                        />
                        <label class="fieldlabels"
                          >Email <span class="required">*</span></label
                        >
                        <input type="email" name="email" placeholder="Email" />
                      </div>
                    </div>
                  </div>
                  <input
                    type="button"
                    name="next"
                    class="next action-button"
                    value="Next"
                  />
                </fieldset>
                <fieldset>
                  <div class="form-card">
                    <div class="row">
                      <div class="col-7">
                        <h2 class="fs-title">Payment in local currency</h2>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12 col-md-5 col-lg-4">
                        <div class="info">
                          <i class="fa-solid fa-info"></i>
                          Info: All payments will be made in the local platform
                          currency (RON).
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                      <label class="fieldlabels"
                        >IBAN <span class="required">*</span></label
                      >
                      <input
                        type="text"
                        name="country location"
                        placeholder="Iban"
                      />
                      <label class="fieldlabels"
                        >Bank <span class="required">*</span></label
                      >
                      <input type="text" name="Bank" placeholder="Bank" />
                      <label class="fieldlabels"
                        >SWIFT <span class="required">*</span></label
                      >
                      <input
                        type="text"
                        name="SWIFT"
                        id=""
                        placeholder="SWIFT"
                      />
                      <label class="fieldlabels"
                        >Local currency (RON)
                        <span class="required">*</span></label
                      >
                      <select name="" id="">
                        <option value=""></option>
                        <option value=""></option>
                        <option value=""></option>
                      </select>
                      <label class="fieldlabels"
                        >I am a romanian producer?
                        <span class="required">*</span></label
                      >
                      <div class="radio-main">
                        <div class="radio-btn">
                          <label for="no">NO</label>
                          <input
                            type="radio"
                            name="VAT payer"
                            placeholder="VAT payer"
                            id="no"
                          />
                        </div>
                        <div class="radio-btn">
                          <label for="yes">YES</label>
                          <input
                            type="radio"
                            name="VAT payer"
                            placeholder="VAT payer"
                            id="yes"
                          />
                        </div>
                      </div>

                      <label class="fieldlabels"
                        >Brief or short company description
                        <span class="required">*</span></label
                      >
                      <textarea name="" id="" cols="30" rows="30"></textarea>
                    </div>
                  </div>
                  <input
                    type="button"
                    name="next"
                    class="next action-button"
                    value="Next"
                  />
                  <input
                    type="button"
                    name="previous"
                    class="previous action-button-previous"
                    value="Previous"
                  />
                </fieldset>
                <fieldset>
                  <div class="form-card">
                    <!-- <div class="row">
                      <div class="col-7">
                        <h2 class="fs-title">Image Upload:</h2>
                      </div>
                      <div class="col-5">
                        <h2 class="steps">Step 3 - 4</h2>
                      </div>
                    </div> -->
                    <div class="row">
                      <div class="col-sm-12 col-md-6 col-lg-6">
                        <label class="fieldlabels"
                          >Certificate of registration
                          <span class="required">*</span>
                        </label>
                        <input type="file" name="pic" accept=".pdf" />
                        <label class="fieldlabels"
                          >ID Card of Signatory
                          <span class="required">*</span></label
                        >
                        <input type="file" name="pic" accept=".pdf" />

                        <label class="fieldlabels"
                          >Bank Statement <span class="required">*</span></label
                        >
                        <input type="file" name="pic" accept=".pdf" />

                        <label class="fieldlabels"
                          >Articles of Incorporation
                          <span class="required">*</span></label
                        >
                        <input type="file" name="pic" accept=".pdf" />
                      </div>
                      <div class="col-sm-12 col-md-6 col-lg-6">
                        <label class="fieldlabels"
                          >First Name <span class="required">*</span></label
                        >
                        <input type="text" name="First Name" />
                        <label class="fieldlabels"
                          >Last Name <span class="required">*</span></label
                        >
                        <input type="text" name="Last Name" />
                        <label class="fieldlabels"
                          >Position <span class="required">*</span></label
                        >
                        <input type="text" name="Position" />

                        <label class="fieldlabels"
                          >Legal Representative's Email
                          <span class="required">*</span></label
                        >
                        <input type="email" name="email" />
                        <label class="fieldlabels"
                          >Confirm Legal Representative's Email
                          <span class="required">*</span></label
                        >
                        <input type="email" name="email" />
                      </div>
                    </div>
                  </div>
                  <input
                    type="button"
                    name="next"
                    class="next action-button"
                    value="Next"
                  />
                  <input
                    type="button"
                    name="previous"
                    class="previous action-button-previous"
                    value="Previous"
                  />
                </fieldset>
                <fieldset>
                  <div class="form-card">
                    <!-- <div class="row">
                      <div class="col-7">
                        <h2 class="fs-title">Image Upload:</h2>
                      </div>
                      <div class="col-5">
                        <h2 class="steps">Step 3 - 4</h2>
                      </div>
                    </div> -->
                    <!-- <label class="fieldlabels">Upload Your Photo:</label>
                    <input type="file" name="pic" accept="image/" /><span
                      class="required"
                      >*</span
                    >
                    <label class="fieldlabels">Upload Signature Photo:</label>
                    <input type="file" name="pic" /><span class="required"
                      >*</span
                    > -->

                    <div class="col-sm-12 col-md-5 col-lg-12">
                      <div class="info">
                        <i class="fa-solid fa-info"></i>
                        Please choose between 1 and 5 product categories that
                        you sell. The list of categories displayed below is
                        generic, so please select the closest categories to your
                        field. This step is mandatory for the continuation of
                        the registration process.
                      </div>
                    </div>
                    <section class="header">
                      <div class="container pb-4">
                        <div class="row">
                          <div class="col-md-3">
                            <!-- Tabs nav -->
                            <div
                              class="nav flex-column nav-pills nav-pills-custom"
                              id="v-pills-tab"
                              role="tablist"
                              aria-orientation="vertical"
                            >
                              <a
                                class="nav-link mb-3 p-3 shadow active"
                                id="v-pills-home-tab"
                                data-toggle="pill"
                                href="#v-pills-home"
                                role="tab"
                                aria-controls="v-pills-home"
                                aria-selected="true"
                              >
                                <span
                                  class="font-weight-bold small text-uppercase"
                                  >Personal information</span
                                ></a
                              >

                              <a
                                class="nav-link mb-3 p-3 shadow"
                                id="v-pills-profile-tab"
                                data-toggle="pill"
                                href="#v-pills-profile"
                                role="tab"
                                aria-controls="v-pills-profile"
                                aria-selected="false"
                              >
                                <span
                                  class="font-weight-bold small text-uppercase"
                                  >Bookings</span
                                ></a
                              >

                              <a
                                class="nav-link mb-3 p-3 shadow"
                                id="v-pills-messages-tab"
                                data-toggle="pill"
                                href="#v-pills-messages"
                                role="tab"
                                aria-controls="v-pills-messages"
                                aria-selected="false"
                              >
                                <span
                                  class="font-weight-bold small text-uppercase"
                                  >Reviews</span
                                ></a
                              >

                              <a
                                class="nav-link mb-3 p-3 shadow"
                                id="v-pills-settings-tab"
                                data-toggle="pill"
                                href="#v-pills-settings"
                                role="tab"
                                aria-controls="v-pills-settings"
                                aria-selected="false"
                              >
                                <span
                                  class="font-weight-bold small text-uppercase"
                                  >Confirm booking</span
                                ></a
                              >
                            </div>
                          </div>

                          <div class="col-md-9">
                            <!-- Tabs content -->
                            <div class="tab-content" id="v-pills-tabContent">
                              <div
                                class="tab-pane fade shadow rounded bg-white show active p-5"
                                id="v-pills-home"
                                role="tabpanel"
                                aria-labelledby="v-pills-home-tab"
                              >
                                <h4 class="font-italic mb-4">
                                  Personal information
                                </h4>
                                <div class="range-checkbox-main">
                                  <div class="check-item">
                                    <input
                                      type="checkbox"
                                      name="cat-check"
                                      id="check"
                                      value="Other Items"
                                    />
                                    <label for="check">Other Items</label>
                                  </div>
                                  <div class="check-item">
                                    <input
                                      type="checkbox"
                                      name="cat-check"
                                      id="check1"
                                      value="SDA Others"
                                    />
                                    <label for="check1">SDA Others</label>
                                  </div>
                                  <div class="check-item">
                                    <input
                                      type="checkbox"
                                      name="cat-check"
                                      id="check2"
                                      value="Other Photos"
                                    />
                                    <label for="check2">Other Photos</label>
                                  </div>
                                  <div class="check-item">
                                    <input
                                      type="checkbox"
                                      name="cat-check"
                                      id="check3"
                                      value="Camera, Videos & Accessories"
                                    />
                                    <label for="check3"
                                      >Camera, Videos & Accessories</label
                                    >
                                  </div>
                                  <div class="check-item">
                                    <input
                                      type="checkbox"
                                      name="cat-check"
                                      id="check4"
                                      value="Gaming Hardware"
                                    />
                                    <label for="check4">Gaming Hardware</label>
                                  </div>
                                  <div class="check-item">
                                    <input
                                      type="checkbox"
                                      name="cat-check"
                                      id="check5"
                                      value="MDA Others"
                                    />
                                    <label for="check5">MDA Others</label>
                                  </div>
                                  <div class="check-item">
                                    <input
                                      type="checkbox"
                                      name="cat-check"
                                      id="check6"
                                      value="AC Others"
                                    />
                                    <label for="check6">AC Others</label>
                                  </div>
                                  <div class="check-item">
                                    <input
                                      type="checkbox"
                                      name="cat-check"
                                      id="check7"
                                      value=""
                                    />
                                    <label for="check7">TV ACC</label>
                                  </div>
                                  <div class="check-item">
                                    <input
                                      type="checkbox"
                                      name="cat-check"
                                      id="check8"
                                      value="Audo Video & Hifi"
                                    />
                                    <label for="check8"
                                      >Audo Video & Hifi</label
                                    >
                                  </div>
                                  <div class="check-item">
                                    <input
                                      type="checkbox"
                                      name="cat-check"
                                      id="check9"
                                      value="Aparate Photo & DSLR"
                                    />
                                    <label for="check9"
                                      >Aparate Photo & DSLR</label
                                    >
                                  </div>
                                </div>
                              </div>

                              <div
                                class="tab-pane fade shadow rounded bg-white p-5"
                                id="v-pills-profile"
                                role="tabpanel"
                                aria-labelledby="v-pills-profile-tab"
                              >
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

                              <div
                                class="tab-pane fade shadow rounded bg-white p-5"
                                id="v-pills-messages"
                                role="tabpanel"
                                aria-labelledby="v-pills-messages-tab"
                              >
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

                              <div
                                class="tab-pane fade shadow rounded bg-white p-5"
                                id="v-pills-settings"
                                role="tabpanel"
                                aria-labelledby="v-pills-settings-tab"
                              >
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

                      <div class="col-sm-12 col-md-5 col-lg-12">
                        <div class="info">
                          <i class="fa-solid fa-info"></i>
                          Uploading an initial list of products will allow us to
                          verify if you have correctly selected the product
                          categories you want to sell on the eMag Marketplace or
                          help you with recommendations for proper
                          categorization.
                        </div>
                      </div>
                    </section>
                    <div class="row">
                      <div class="col-sm-12 col-md-6 col-lg-6">
                        <label class="fieldlabels"
                          >Example for initial loading of products
                          <span class="required">*</span>
                        </label>
                        <a href="#" download="" class="d-block download-link"
                          >download the example with the initial list of
                          products</a
                        >
                      </div>
                      <div class="col-sm-12 col-md-6 col-lg-6">
                        <label class="fieldlabels"
                          >Upload an initial list of products
                          <span class="required">*</span>
                        </label>
                        <input type="file" name="pic" accept=".xlsx, xlx" />
                      </div>
                    </div>
                  </div>
                  <input
                    type="button"
                    name="next"
                    class="next action-button"
                    value="Next"
                  />
                  <input
                    type="button"
                    name="previous"
                    class="previous action-button-previous"
                    value="Previous"
                  />
                </fieldset>
                <fieldset>
                  <div class="form-card">
                    <div class="row">
                      <div class="col-sm-12 col-md-6 col-lg-6">
                        <h2 class="fs-title">Order Processing</h2>
                        <label class="fieldlabels"
                          >Return Period <span class="required">*</span></label
                        >
                        <div class="radio-main last-radio-warp">
                          <div class="radio-btn last-radio-btn">
                            <label for="14days">14 Days</label>
                            <input
                              type="radio"
                              name="VAT payer"
                              placeholder="VAT payer"
                              id="14days"
                            />
                          </div>
                          <div class="radio-btn last-radio-btn">
                            <label for="30days">30 Days</label>
                            <input
                              type="radio"
                              name="VAT payer"
                              placeholder="VAT payer"
                              id="30days"
                            />
                          </div>
                          <div class="radio-btn last-radio-btn">
                            <label for="60days">60 Days</label>
                            <input
                              type="radio"
                              name="VAT payer"
                              placeholder="VAT payer"
                              id="60days"
                            />
                          </div>
                          <div class="radio-btn last-radio-btn">
                            <label for="90days">90 Days</label>
                            <input
                              type="radio"
                              name="VAT payer"
                              placeholder="VAT payer"
                              id="90days"
                            />
                          </div>
                        </div>
                        <label class="fieldlabels"
                          >Maximum Order Processing Time
                          <span class="required">*</span></label
                        >
                        <input type="time" name="passing time" />
                        <p class="note-input-text">
                          Represents the maximum time by which you can prepare
                          orders for delivery placed by customers.
                        </p>
                      </div>

                      <div class="col-sm-12 col-md-6 col-lg-6">
                        <h2 class="fs-title">Delivery Fee</h2>
                        
                        <label class="fieldlabels"
                          >The transportation fee paid by the customer to the courier for delivery <span class="required">*</span></label
                        >
                        <input type="text" name="passing time" />

                        <label class="fieldlabels"
                          >Free Shipping Threshold <span class="required">*</span></label
                        >
                        <input type="text" name="passing time" />
                      </div>
                    </div>
                  </div>
                  <input
                    type="submit"
                    name="next"
                    class="next action-button"
                    value="submit"
                  />
                  <input
                    type="button"
                    name="previous"
                    class="previous action-button-previous"
                    value="Previous"
                  />
                </fieldset>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endsection