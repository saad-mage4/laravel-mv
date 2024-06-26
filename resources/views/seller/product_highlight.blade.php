@extends('seller.master_layout')
@section('title')
    <title>{{__('user.Product Highlight')}}</title>
@endsection
@section('seller-content')

<style>
    #card-element {
        margin-bottom: 20px;
        border: 1px solid #ccc;
        padding: 10px;
        max-width:450px;
    }

    button[type="submit"] {
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button[type="submit"]:hover {
        background-color: #0056b3;
    }
    .highlight-text {
        display: flex;
        flex-direction: column;
    }
    .radio-groups {
        display: flex;
        gap:20px;
        margin-block:15px;
    }
    .radio-groups label {
        color: #000;
        font-weight: bold;
        letter-spacing: 1px;
        margin-right: 10px;
    }
    .radio-group * {
        margin: 0;
        padding: 0;
    }
    .radio-group {
        display: flex;
        align-items: center;
        gap: 10px;
        background-color: #fff;
        border: 2px solid #007bff;
        padding:5px;
        border-radius:5px;
    }
    .radio-group.active {
        background-color: #007bff;
    }
    .radio-group.active label {
        color: #fff;
    }
    .highlight-text {
        color: #444;
    }
    .highlight-text .highlight {
        font-weight: bold;
        color: #000;
    }
    .highlight-text span:not(.highlight) {
        margin-left: 10px;
        color: #222;
    }
</style>
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{__('user.Product Highlight')}}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a
                            href="{{ route('seller.dashboard') }}">{{__('user.Dashboard')}}</a></div>
                    <div class="breadcrumb-item">{{__('user.Product Highlight')}}</div>
                </div>
            </div>

            <div class="section-body">
                <a href="{{ route('seller.product.index') }}" class="btn btn-primary"><i
                        class="fas fa-list"></i> {{__('user.Products')}}</a>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                @if($product->is_highlight_1 != 0)
                                <form action="{{ route('seller.update-product-highlight',$product->id) }}"
                                      method="POST">
                                    @method('PUT')
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label>{{__('user.Select Type')}} <span class="text-danger">*</span></label>
                                            <select name="product_type" class="form-control" id="product_type">
{{--                                                <option--}}
{{--                                                    {{ $product->is_undefine == 1 ? 'selected' : '' }} value="1">{{__('user.Undefine Product')}}</option>--}}
                                                <option
                                                    {{ $product->new_product == 1 ? 'selected' : '' }} value="2">{{__('user.New Arrival')}}</option>
                                                <option
                                                    {{ $product->is_featured == 1 ? 'selected' : '' }} value="3">{{__('user.Featured Product')}}</option>
                                                <option
                                                    {{ $product->is_top == 1 ? 'selected' : '' }} value="4">{{__('user.Top Product')}}</option>
                                                <option
                                                    {{ $product->is_best == 1 ? 'selected' : '' }} value="5">{{__('user.Best Product')}}</option>
{{--                                                <option--}}
{{--                                                    {{ $product->is_flash_deal == 1 ? 'selected' : '' }} value="6">{{__('user.Flash Deal Product')}}</option>--}}

                                            </select>
                                        </div>
                                        @if ($product->is_flash_deal == 1)
                                            <div class="form-group col-12" id="dateBox">
                                                <label for="">{{__('user.Enter Date')}}</label>
                                                <input type="text" name="date" class="form-control datepicker"
                                                       value="{{ $product->flash_deal_date }}" autocomplete="off">
                                            </div>
                                        @else
                                            <div class="form-group col-12 d-none" id="dateBox">
                                                <label for="">{{__('user.Enter Date')}}</label>
                                                <input type="text" name="date" class="form-control datepicker"
                                                       autocomplete="off">
                                            </div>
                                        @endif
                                    </div>
                                    <div class="row" @if($product->new_product == 1 || $product->is_featured == 1 || $product->is_best == 1 || $product->is_top == 1) style="display: none;" @endif>
                                        <div class="col-12">
                                            <button class="btn btn-primary">{{__('user.Update')}}</button>
                                        </div>
                                    </div>
                                </form>
                                @endif
                                <form id="payment-form" method="POST" action="{{ route('seller.product.highlight.payment', ['id' => $product->id]) }}" style="{{ $product->is_highlight_1 == 0 ? 'display: block;' : 'display: none;' }}">
                                    @csrf
                                    <div class="highlight-text">
                                        <span class="highlight">Upgrade your Product Highlight:</span>
                                        <span>1-week highlight: €5</span>
                                        <span>2-week highlight: €10</span>
                                        Please proceed with payment to activate your chosen option.
                                    </div>
                                    <div class="radio-groups">
                                        <div class="radio-group active">
                                            <input type="radio" checked id="5-euro" name="amount" value="5">
                                            <label for="5-euro">5 Euros</label>
                                        </div>
                                        <div class="radio-group">
                                            <input type="radio" id="10-euro" name="amount" value="10">
                                            <label for="10-euro">10 Euros</label>
                                        </div>
                                    </div>
                                    <!-- Include Stripe Elements for collecting card details -->
                                    <div id="card-element"><!-- Stripe Elements will be inserted here --></div>

                                    <!-- Include input fields for additional payment information (e.g., email) -->

                                    <!-- Include a hidden input for the generated token -->
                                    <input type="hidden" name="stripeToken" id="stripeToken" value="">


                                    <button type="submit">Submit Payment</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


    <script src="https://js.stripe.com/v3/"></script>
    <script>
        $(document).ready(function(){
            $('.radio-groups').on('change', 'input', function(e){
                e.preventDefault();
                $(this).parent('.radio-group').addClass('active').siblings('.radio-group').removeClass('active');
            });
        });

        var stripe = Stripe('sk_test_51MznwlDXpr0Gb5SYft0B7AkHv5lK6Fk1hQT86iQbT3h5LG83K2KLhMT38zaivFB35Tesf4Rd2G0iVK7NjrQDEIB000nQIvKgS5');
        var elements = stripe.elements();

        // Create an instance of the card Element
        var cardElement = elements.create('card');

        // Add an instance of the card Element into the `card-element` div
        cardElement.mount('#card-element');

        var form = document.getElementById('payment-form');

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            stripe.createToken(cardElement).then(function(result) {
                if (result.error) {
                    console.error(result.error.message);
                } else {
                    // Set the generated token in the hidden input field
                    document.getElementById('stripeToken').value = result.token.id;
                    // Submit the form
                    form.submit();
                }
            });
        });
    </script>
    <script>
        // JavaScript to toggle visibility based on $product->is_highlight_1 value
        const updateButton = document.getElementById('updateButton');
        const paymentForm = document.getElementById('payment-form');

        // Initially hide the update button if is_highlight_1 is 1
        if ({{ $product->is_highlight_1 }} === 1) {
            updateButton.style.display = 'none';
            paymentForm.style.display = 'block';
        }

        // Function to toggle visibility based on is_highlight_1 value
        function toggleFormVisibility() {
            if ({{ $product->is_highlight_1 }} === 1) {
                updateButton.style.display = 'none';
                paymentForm.style.display = 'block';
            } else {
                updateButton.style.display = 'block';
                paymentForm.style.display = 'none';
            }
        }
    </script>


@endsection
