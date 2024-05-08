@extends('seller.master_layout')
@section('title')
    <title>{{__('user.Product Highlight')}}</title>
@endsection
@section('seller-content')
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
                                <form action="{{ route('seller.update-product-highlight',$product->id) }}"
                                      method="POST">
                                    @method('PUT')
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label>{{__('user.Select Type')}} <span class="text-danger">*</span></label>
                                            <select name="product_type" class="form-control" id="product_type">
                                                <option
                                                    {{ $product->is_undefine == 1 ? 'selected' : '' }} value="1">{{__('user.Undefine Product')}}</option>
                                                <option
                                                    {{ $product->new_product == 1 ? 'selected' : '' }} value="2">{{__('user.New Arrival')}}</option>
                                                <option
                                                    {{ $product->is_featured == 1 ? 'selected' : '' }} value="3">{{__('user.Featured Product')}}</option>
                                                <option
                                                    {{ $product->is_top == 1 ? 'selected' : '' }} value="4">{{__('user.Top Product')}}</option>
                                                <option
                                                    {{ $product->is_best == 1 ? 'selected' : '' }} value="5">{{__('user.Best Product')}}</option>
                                                <option
                                                    {{ $product->is_flash_deal == 1 ? 'selected' : '' }} value="6">{{__('user.Flash Deal Product')}}</option>

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
{{--                                    <div class="row">--}}
{{--                                        <div class="col-12">--}}
{{--                                            <button class="btn btn-primary">{{__('user.Update')}}</button>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                    <div class="row" style="{{ $product->is_highlight_1 == 0 ? 'display: none;' : '' }}">
                                        <div class="col-12">
                                            <button class="btn btn-primary">{{__('user.Update')}}</button>
                                        </div>
                                    </div>
                                </form>
                                <form id="payment-form" method="POST" action="{{ route('seller.product.highlight.payment', ['id' => $product->id]) }}" style="{{ $product->is_highlight_1 == 0 ? 'display: block;' : 'display: none;' }}">
                                    @csrf
                                    <!-- Include Stripe Elements for collecting card details -->
                                    <div id="card-element"><!-- Stripe Elements will be inserted here --></div>

                                    <!-- Include input fields for additional payment information (e.g., email) -->
                                    <input type="email" name="email" placeholder="Email">

                                    <!-- Include a hidden input for the generated token -->
                                    <input type="hidden" name="stripeToken" id="stripeToken" value="">

                                    <input type="radio" id="amount" name="amount" value="5">
                                    <label for="html">5 Euros</label><br>
                                    <input type="radio" id="amount" name="amount" value="10">
                                    <label for="css">10 Euros</label><br>

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
        var stripe = Stripe('pk_test_51OJfb1A7He8cL1jepIHz4KyBS3mWU2807SOLepFT5YwTXkCs2T5wOABneh4dzGih7k1lzxv4U1ICQuZfHMBKxtWj002WMt9Wjq');
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
