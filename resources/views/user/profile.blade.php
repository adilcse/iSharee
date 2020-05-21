@extends('layouts.app')

@push('script')
<script src="{{ asset('js/user/profile.js') }}" defer></script>
@endpush

@section('content')

@php
    $action=url('/user/update');
@endphp

<div class="container">
    @if(session('status'))
        <div class="row alert alert-success">
            {{session('status')}}
        </div>
    @endif
    @foreach($errors->all() as $error)
        <div class="row alert alert-danger">
            {{$error}}
        </div>
    @endforeach
    <div class="row justify-content-center">
        <div class="col col-md-7">
            <!-- Card -->
            <h4><a class="text-primary" href="{{route('userArticles',$profile->id)}}"> view Articles</a></h4>
            <div class="card ">
            <!-- Background color -->
                <div class="card-up indigo lighten-1"></div>
                <!-- Avatar -->
                <div class="avatar mx-auto white">
                    <img src="{{asset('image/article.png')}}" width="200px" height="200px" class="rounded-circle" alt="avatar">
                </div>
                <h4 class="text-center"> user profile </h4>
                <!-- Content -->
                <div class="card-body">
                    <h6 class="text-center"> verified data can't be changed </h6>
                    <hr>
                    <!-- Form -->
                    <form method="POST" action="{{$action}}"  class="text-center" style="color: #757575;">
                        @csrf
                        <div class="form-row">
                            <input hidden name="id" value="{{$profile->id}}"/>
                                <!-- First name -->
                            <div class="col">
                                <div class="md-form">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $profile->name }}" placeholder="{{ __('Name') }}" required autocomplete="name" autofocus>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <!--name -->
                                <div class="md-form">
                                    <input type="text" id="mobile" class="form-control @error('mobile') is-invalid @enderror" name="mobile" requirded autocomolete="number" placeholder="{{ __('Mobile') }}" value="{{$profile->mobile}}" {{$profile->is_mobile_verified? 'disabled': ''}}>
                                    @if(!$profile->is_mobile_verified)
                                        <!-- change toute to 'mobileVerify' after twilo account resumes -->
                                        <div class="btn btn-primary" data-target="{{route('mobileVerify')}}" id="verify-mobile-btn">Verify</div>
                                    @endif
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                    <span class="invalid-feedback" role="alert" style="display:none">
                                        <strong>invalid number</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- E-mail -->
                        <div class="md-form mt-0">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $profile->email }}" required autocomplete="email" placeholder="{{__('Email')}}" {{$profile->is_email_verified ? 'disabled': ''}}>
                            @if(!$profile->is_email_verified)
                                <button type="button" class="btn btn-primary">Verify</button>
                            @endif
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="changePasswordCheck" name="changePasswordCheck" onClick="changePasswordClick(this)">
                                <label class="form-check-label" for="changePasswordCheck">Change Password</label>
                            </div>
                        </div>
                        <div class="ml-auto mr-auto justify-content-center col-md-8 password-section" style="display:none">
                            @if(!Auth::user()->oauth_token)
                                <div class="md-form row">
                                    <input type="password" id="old-password" name="oldPassword" class="form-control" placeholder="old password">
                                </div>
                            @endif
                            <div class="md-form row">
                                <input type="password" id="password" name="newPassword" class="form-control" aria-describedby="describe-password" placeholder="New password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small id="describe-password" class="form-text text-muted mb-4">
                                    At least 6 charecters
                                </small>
                            </div>
                            <div class="md-form row">
                                <input type="password" name="cPassword" id="cpassword" class="form-control" placeholder="Confirm Password" >
                            </div>
                        </div>
                        <br>
                        <input class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit" value="update"></input>             
                    </form>
                    <!-- Form -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection