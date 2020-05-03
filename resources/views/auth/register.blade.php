@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        @php
            $action=route('register');
            $login_route=route('login');
            if(isset($admin)){
            $action=route('admin.register');
            $login_route=route('admin.login');
            }
            else
            $admin=false;
        @endphp
            <!-- Material form register -->
            <div class="card">

            <h5 class="card-header info-color white-text text-center py-4">
                <strong>{{$admin? 'Admin '.__('Register'):__('Register')}}</strong>
            </h5>

            <!--Card content-->
            <div class="card-body px-lg-5 pt-0">

                <!-- Form -->
                <form method="POST" action="{{$action}}"  class="text-center" style="color: #757575;">
                @csrf

                    <div class="form-row">
                            <!-- First name -->
                        <div class="col">
                            <div class="md-form">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="{{ __('Name') }}" required autocomplete="name" autofocus>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            </div>
                        </div>
                        <div class="col">
                            <!-- Last name -->
                            <div class="md-form">
                                <input type="text" id="mobile" class="form-control @error('mobile') is-invalid @enderror" name="mobile" requirded autocomolete="number" placeholder="{{ __('Mobile') }}">
                            </div>
                        </div>
                    </div>
                    <!-- E-mail -->
                    <div class="md-form mt-0">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="{{__('Email')}}">

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    </div>

                    <!-- Password -->
                    <div class="md-form">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="{{__('Password')}}">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                        <small id="passwordHelp" class="form-text text-muted mb-4">
                            Atleast 6 charecters
                        </small>
                    </div>
                    <div class="md-form">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm password">
                    </div>

                    <!-- Sign up button -->
                    <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">
                    {{ __('Register') }}
                    </button>


                </form>
                <!-- Form -->
                <div class='text-center text-muted'>
                <h5>
                    Already have an account ? <a href={{$login_route}}>Login</a>
                </h5>
                </div>
            </div>

            </div>
<!-- Material form register -->

    </div>
</div>
@endsection
