@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <!-- Material form register -->
<div class="card">

<h5 class="card-header info-color white-text text-center py-4">
    <strong>Verify Email</strong>
</h5>

<!--Card content-->
<div class="card-body px-lg-5 pt-0">

    <!-- Form -->
    <form class="text-center" style="color: #757575;" action="/email/verify/otp" method="post">
    @csrf
    @php
        $email=urldecode($email);
    @endphp

        <p>An OTP has been sent to your email address</p>

        <!-- E-mai -->
        <div class="md-form">
            <div class="row">
            <div class="col-md-8">
                <input type="email" id="email" name="email" class="form-control" value="{{$email}}" placeholder="{{__('Email')}}" disabled>
            </div>
            <div class="col-md-4">
                <button class="btn btn-outline-info btn-rounded z-depth-0 btn-block waves-effect" type="button">
                    <a href="?resend=true"> Resend</a>
                </button>
            </div>
            </div>
        </div>
            <input type="hidden" name="mail" value="{{$email}}">
        <!-- Name -->
        <div class="md-form mt-3">
            <input type="text" id="otp" name="otp" class="form-control" placeholder="OTP">
            @if(isset($error))
            <div class="alert alert-danger" role="alert">
                invalid {{$error}}
                </div>
                @endif
        </div>
        <!-- Sign in button -->
        <button class="btn btn-outline-info btn-rounded btn-block z-depth-0 my-4 waves-effect" type="submit">
            Verify
        </button>

    </form>
    <!-- Form -->

</div>

</div>
<!-- Material form subscription -->
    </div>
</div>

@endsection
