@extends('layouts.app')
<!-- payment page not assigned -->
@push('script')
    <script src="https://js.stripe.com/v3/" defer></script>
    <script src="{{asset('js/post/payment.min.js')}}" defer></script>
    
@endpush
@push('head')
<link rel="stylesheet" href="{{asset('css/payment.min.css')}}"></link>
@endpush


@if(!isset($data)){
    <script>window.location = "/error";</script>
}
@endif
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
                        <form action="{{route('stripe.post')}}" method="post" id="payment-form" data-stripe="{{env('STRIPE_P_KEY')}}">
                        @csrf
                        <input hidden name='orderId' value="{{$data['articleId']}}">
                        <div class="form-row">
                            <label for="card-element">
                            Credit or debit card
                            </label>
                            <div id="card-element" class="container">
                            <!-- A Stripe Element will be inserted here. -->
                            </div>
                            <!-- Used to display form errors. -->
                            <div id="card-errors" role="alert"></div>
                        </div>
                        <button class="btn btn-primary">Pay ₹99</button> 
                        <a href="{{route('article',$data['slug']??'1')}}" class="btn btn-warning">Cancel payment</a>
                    </form>
                    <!-- Form -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
