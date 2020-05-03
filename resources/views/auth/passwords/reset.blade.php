@extends('layouts.app')

@section('content')
@push('head')
<!-- Scripts -->
<script src="{{ asset('js/password/reset.js') }}" defer></script>

@endpush

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        <div class="card">

<h5 class="card-header info-color white-text text-center py-4">
    <strong>Reset password</strong>
</h5>

<!--Card content-->
<div class="card-body px-lg-5 pt-0">

    <!-- Form -->
    <form class="text-center" style="color: #757575;" action="/email/verify/otp" method="post">
    @csrf

    @php
        if(isset($email))
            $email=urldecode($email);
        else
            $email='';
    @endphp
        <div id='status' class="alert">
        </div>

        <!-- E-mai -->
        <div class="md-form">
            <div class="row">
            <div class="col-md-8">
                <input type="email" id="email" name="email" class="form-control" value="{{$email ?? ''}}" placeholder="{{__('Email')}}">
                <input type="email" id="mail" name="mail" hidden>
            </div>
            <div class="col-md-4">
                <button class="btn btn-outline-info btn-rounded z-depth-0 btn-block waves-effect" type="button" id="sendbtn">
                    Send
                </button>
            </div>
            </div>
        </div>
        <!-- OTP -->
        <div class="md-form mt-3">
            <input type="text" id="otp" name="otp" class="form-control" placeholder="OTP">
            @if(isset($error))
            <div class="alert alert-danger" role="alert">
                invalid {{$error}}
                </div>
                @endif
        </div>
        <div class="md-form mt-3">
            <input type="password" id="password" name="password" class="form-control" placeholder="password" required>
        </div>
        <div class="md-form mt-3">
            <input type="text" id="cpassword" name="cpassword" class="form-control" placeholder="confirm password" >
        </div>

        <!-- Sign in button -->
        <button class="btn btn-outline-info btn-rounded btn-block z-depth-0 my-4 waves-effect" id="verify" type="button" >
            Verify
        </button>

    </form>
    <!-- Form -->

</div>

        </div>
    </div>
</div>
@endsection
