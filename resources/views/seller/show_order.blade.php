@extends('seller.master_layout')
@section('title')
<title>{{__('user.Invoice')}}</title>
@endsection
<style>
    @media print {
        .section-header,
        .order-status,
        #sidebar-wrapper,
        .print-area,
        .main-footer {
            display:none!important;
        }
    }
</style>
@section('seller-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('user.Invoice')}}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('seller.dashboard') }}">{{__('user.Dashboard')}}</a></div>
              <div class="breadcrumb-item">{{__('user.Invoice')}}</div>
            </div>
          </div>
          <div class="section-body">
            <div class="invoice">
              <div class="invoice-print">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="invoice-title">
                      <h2><img src="{{ asset($setting->logo) }}" alt="" width="120px"></h2>
                      <div class="invoice-number">{{__('user.Order')}} #{{ $order->order_id }}</div>
                    </div>
                    <hr>
                    @php
                        $orderAddress = $order->orderAddress;
                    @endphp
                    <div class="row">
                      <div class="col-md-6">
                        <address>
                          <strong>{{__('user.Billing Information')}}:</strong><br>
                            {{ $orderAddress->billing_first_name . ' '.$orderAddress->billing_last_name }}<br>
                            @if ($orderAddress->billing_email)
                            {{ $orderAddress->billing_email }}<br>
                            @endif
                            @if ($orderAddress->billing_phone)
                            {{ $orderAddress->billing_phone }}<br>
                            @endif
                            {{ $orderAddress->billing_address }},
                            {{ $orderAddress->billing_city.', '. $orderAddress->billing_state.', '.$orderAddress->billing_country }}<br>
                        </address>
                      </div>
                      <div class="col-md-6 text-md-right">
                        <address>
                          <strong>{{__('user.Shipping Information')}} :</strong><br>
                          {{ $orderAddress->shipping_first_name . ' '.$orderAddress->shipping_last_name }}<br>
                            @if ($orderAddress->shipping_email)
                            {{ $orderAddress->shipping_email }}<br>
                            @endif
                            @if ($orderAddress->shipping_phone)
                            {{ $orderAddress->shipping_phone }}<br>
                            @endif
                            {{ $orderAddress->shipping_address }},
                            {{ $orderAddress->shipping_city.', '. $orderAddress->shipping_state.', '.$orderAddress->shipping_country }}<br>
                        </address>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <address>
                          <strong>{{__('user.Payment Information')}}:</strong><br>
                          {{__('user.Method')}}: {{ $order->payment_method }}<br>
                          {{__('user.Status')}} : @if ($order->payment_status == 1)
                              <span class="badge badge-success">{{__('user.Success')}}</span>
                              @else
                              <span class="badge badge-danger">{{__('user.Pending')}}</span>
                          @endif
                          <br>
                          {{__('user.Transaction')}}: {{ $order->transection_id }}
                        </address>
                      </div>
                      <div class="col-md-6 text-md-right">
                        <address>
                          <strong>{{__('user.Order Information')}}:</strong><br>
                          {{__('user.Date')}}: {{ $order->created_at->format('d F, Y') }}<br>
                          {{__('user.Shipping')}}: {{ $order->shipping_method }}<br>
                          {{__('user.Status')}} :
                          @if ($order->order_status == 1)
                          <span class="badge badge-success">{{__('user.Pregress')}} </span>
                          @elseif ($order->order_status == 2)
                          <span class="badge badge-success">{{__('user.Delivered')}} </span>
                          @elseif ($order->order_status == 3)
                          <span class="badge badge-success">{{__('user.Completed')}} </span>
                          @elseif ($order->order_status == 4)
                          <span class="badge badge-danger">{{__('user.Declined')}} </span>
                          @else
                          <span class="badge badge-danger">{{__('user.Pending')}}</span>
                        @endif
                        </address>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row mt-4">
                  <div class="col-md-12">
                    <div class="section-title">{{__('user.Order Summary')}}</div>
                    <div class="table-responsive">
                      <table class="table table-striped table-hover table-md">
                        <tr>
                          <th width="5%">#</th>
                          <th width="25%">{{__('user.Product')}}</th>
                          <th width="20%">{{__('user.Variant')}}</th>
                          <th width="10%">{{__('user.Shop Name')}}</th>
                          <th width="10%" class="text-center">{{__('user.Unit Price')}}</th>
                          <th width="10%" class="text-center">{{__('user.Quantity')}}</th>
                          <th width="10%" class="text-right">{{__('user.Total')}}</th>
                        </tr>
                        @php
                            $subTotal = 0;
                        @endphp
                        @foreach ($order->orderProducts as $index => $orderProduct)
                            @php
                                $variantPrice = 0;
                                $totalVariant = $orderProduct->orderProductVariants->count();
                                $product = App\Models\Product::where('id',$orderProduct->product_id)->first();
                            @endphp
                            <tr>
                                <td>{{ ++$index }}</td>
                                <td><a href="{{ route('product-detail', $product->slug) }}">{{ $orderProduct->product_name }}</a></td>
                                <td>
                                    @foreach ($orderProduct->orderProductVariants as $indx => $variant)
                                        {{ $variant->variant_name.' : '.$variant->variant_value }}{{ $totalVariant == ++$indx ? '' : ',' }}
                                        <br>
                                        @php
                                            $variantPrice += $variant->variant_price;
                                        @endphp
                                    @endforeach

                                </td>
                                <td>
                                    @if ($orderProduct->seller)
                                        <a href="{{ route('seller-detail',['shop_name' => $orderProduct->seller->slug]) }}">{{  $orderProduct->seller->shop_name }}</a>
                                    @endif
                                </td>
                                <td class="text-center">{{ $setting->currency_icon }}{{ $orderProduct->unit_price }}</td>
                                <td class="text-center">{{ $orderProduct->qty }}</td>
                                @php
                                    $total = ($orderProduct->unit_price * $orderProduct->qty)
                                @endphp
                                <td class="text-right">{{ $setting->currency_icon }}{{ $total }}</td>
                            </tr>
                            @php
                                $totalVariant = 0;
                            @endphp
                        @endforeach
                      </table>
                    </div>
                      <div class="row mt-4">
                          <div class="col-lg-6 order-status">
                              <div class="section-title">{{__('user.Order Status')}}</div> <!-- Change 'admin' to 'user' -->
                              <form action="{{ route('seller.update-order-status', ['id' => $order->id]) }}" method="POST">
                                  @csrf
                                  @method("PUT")
                                  <div class="form-group">
                                      <label for="">{{__('user.Payment')}}</label> <!-- Change 'admin' to 'user' -->
                                      <select name="payment_status" id="" class="form-control">
                                          <option {{ $order->payment_status == 0 ? 'selected' : '' }} value="0">{{__('user.Pending')}}</option> <!-- Change 'admin' to 'user' -->
                                          <option {{ $order->payment_status == 1 ? 'selected' : '' }} value="1">{{__('user.Success')}}</option> <!-- Change 'admin' to 'user' -->
                                      </select>
                                  </div>

                                  <div class="form-group">
                                      <label for="">{{__('user.Order')}}</label> <!-- Change 'admin' to 'user' -->
                                      <select name="order_status" id="" class="form-control">
                                          <option {{ $order->order_status == 0 ? 'selected' : '' }} value="0">{{__('user.Pending')}}</option> <!-- Change 'admin' to 'user' -->
                                          <option {{ $order->order_status == 1 ? 'selected' : '' }} value="1">{{__('user.In Progress')}}</option> <!-- Change 'admin' to 'user' -->
                                          <option {{ $order->order_status == 2 ? 'selected' : '' }} value="2">{{__('user.Delivered')}}</option> <!-- Change 'admin' to 'user' -->
                                          <option {{ $order->order_status == 3 ? 'selected' : '' }} value="3">{{__('user.Completed')}}</option> <!-- Change 'admin' to 'user' -->
                                          <option {{ $order->order_status == 4 ? 'selected' : '' }} value="4">{{__('user.Declined')}}</option> <!-- Change 'admin' to 'user' -->
                                      </select>
                                  </div>

                                  <button class="btn btn-primary" type="submit">{{__('user.Update Status')}}</button>
                              </form>
                          </div>

                          <div class="col-lg-6 text-right">
                              <div class="invoice-detail-item">
                                  <div class="invoice-detail-name">{{__('user.Subtotal')}} : {{ $setting->currency_icon }}{{ $order->sub_total }}</div>
                              </div>
                              <div class="invoice-detail-item">
                                  <div class="invoice-detail-name">{{__('user.Discount')}}(-) : {{ $setting->currency_icon }}{{ $order->coupon_coast }}</div>
                              </div>
                              <div class="invoice-detail-item">
                                  <div class="invoice-detail-name">{{__('user.Shipping')}} : {{ $setting->currency_icon }}{{ $order->shipping_cost }}</div>
                              </div>
                              <div class="invoice-detail-item">
                                  <div class="invoice-detail-name">{{__('user.Tax')}} : {{ $setting->currency_icon }}{{ $order->order_vat }}</div>
                              </div>

                              <hr class="mt-2 mb-2">
                              <div class="invoice-detail-item">
                                  <div class="invoice-detail-value invoice-detail-value-lg">{{__('user.Total')}} : {{ $setting->currency_icon }}{{ $order->amount_real_currency }}</div>
                              </div>
                          </div>
                      </div>
{{--                    <div class="row mt-4">--}}
{{--                      <div class="col-lg-6 order-status">--}}
{{--                      </div>--}}

{{--                      <div class="col-lg-6 text-right">--}}
{{--                        <div class="invoice-detail-item">--}}
{{--                            <div class="invoice-detail-name">{{__('user.Subtotal')}} : {{ $setting->currency_icon }}{{ $order->sub_total }}</div>--}}
{{--                          </div>--}}
{{--                          <div class="invoice-detail-item">--}}
{{--                            <div class="invoice-detail-name">{{__('user.Discount')}}(-) : {{ $setting->currency_icon }}{{ $order->coupon_coast }}</div>--}}
{{--                          </div>--}}
{{--                          <div class="invoice-detail-item">--}}
{{--                            <div class="invoice-detail-name">{{__('user.Shipping')}} : {{ $setting->currency_icon }}{{ $order->shipping_cost }}</div>--}}
{{--                          </div>--}}
{{--                          <div class="invoice-detail-item">--}}
{{--                            <div class="invoice-detail-name">{{__('user.Tax')}} : {{ $setting->currency_icon }}{{ $order->order_vat }}</div>--}}
{{--                          </div>--}}



{{--                        <hr class="mt-2 mb-2">--}}
{{--                        <div class="invoice-detail-item">--}}
{{--                          <div class="invoice-detail-value invoice-detail-value-lg">{{__('user.Total')}} : {{ $setting->currency_icon }}{{ $order->amount_real_currency }}</div>--}}
{{--                        </div>--}}
{{--                      </div>--}}

{{--                    </div>--}}
{{--                  </div>--}}
{{--                </div>--}}
              </div>

              <div class="text-md-right print-area">
                <hr>
                <button class="btn btn-success btn-icon icon-left" onclick="window.print()"><i class="fas fa-print"></i> {{__('user.Print')}}</button>
              </div>
            </div>
          </div>

        </section>
      </div>
@endsection
