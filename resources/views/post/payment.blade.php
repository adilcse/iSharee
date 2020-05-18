@extends('layouts.app')
<!-- payment page not assigned -->
@push('script')
    <script src="{{asset('js/post/payment.js')}}" defer></script>
@endpush
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Material add article page -->
            <div class="card">
                <h5 class="card-header info-color white-text text-center py-4">
                    <strong>Payment</strong>
                </h5>
                <!--Card content-->
                <div class="card-body px-lg-5 pt-0">
                    @if(session('status'))
                    <div class="alert alert-success">
                        {{session('status')}}
                    </div>
                    @endif
                    @foreach($errors->all() as $error)
                    <div class="alert alert-danger">
                        {{$error}}
                    </div>
                    @endforeach
                    <div class="display-td text-center" >                            
                        <img class="img-responsive pull-right" src="http://i76.imgup.net/accepted_c22e0.png">
                    </div>
                    <!-- Form -->
                    <form class="text-center" style="color: #757575;" action="{{ route('stripe.post') }}" 
                        method="post" class="require-validation" data-cc-on-file="false"
                        data-stripe-publishable-key="{{ env('STRIPE_P_KEY') }}" id="payment-form">
                        @csrf
                        <!-- Name -->
                        <div class="md-form mt-3">
                            <input type="text" id="cardName" name="cardName" class="form-control" placeholder="Name on Card">
                            <span class="invalid-feedback" style="display:{{$errors->has('cardName')?'show':'none'}}" role="alert">
                                <strong>{{ $errors->first('cardName')??'' }}</strong>
                            </span>
                        </div>
                        <div class='md-form mt-2'>
                            <input autocomplete='off' name="cardNumber" class='form-control card-number' size='20' placeholder="Card Number" type='text'>
                        </div>

                            <!-- image upload -->
                        <br/>
                        <div class='row'>
                            <div class="col-md-3">
                                <input autocomplete='off' name="cvv" class='form-control card-cvc' placeholder='CVV' size='3' type='text'>
                            </div>
                            <div class='col-md-3'>
                                <input class='form-control card-expiry-month' name="month" placeholder='MM' size='2'  type='text'>
                            </div>
                            <div class='col-md-3'>
                                <input class='form-control card-expiry-year' name="year" placeholder='YYYY' size='4'  type='text'>
                            </div>
                        </div>   
                        <!-- Send button -->
                        <div id="error" class="alert alert-danger mb-3" style="display:none"></div>
                        <button class="btn btn-outline-info btn-rounded z-depth-0 my-4 waves-effect" type="submit">Pay ₹99</button>
                    </form>
                    <!-- Form -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
