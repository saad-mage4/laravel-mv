<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pay with paypal</title>
    <link rel="stylesheet" href="{{ asset('user/css/bootstrap.min.css') }}">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <a href="{{ route('user.checkout.pay-with-paypal-from-api',['agree_terms_condition' => $agree_terms_condition, 'shipping_method' => $shipping_method, 'token' => $token]) }}" type="submit" class="btn btn-primary">{{__('user.Pay with paypal')}}</a>

                {{-- @php
                    $url = '?agree_terms_condition='.$agree_terms_condition.'&shipping_method='.$shipping_method.'&token='.$token;
                @endphp

                <a href="{{ url('api/user/checkout/pay-with-paypal'.$url) }}" type="submit" class="btn btn-primary">{{__('user.Pay with paypal')}}</a> --}}
            </div>
        </div>
    </div>
</body>
</html>
