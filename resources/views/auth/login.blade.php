@extends('layouts.app')

@section('content')
<!-- user login page -->
@push('script')
<!-- Scripts -->
<script src="{{ asset('js/auth/login.js') }}"></script>
@endpush
@php
	$action=route('login');
	$register_route=route('register');
	if(isset($admin))
		$action=route('admin.login');
	else
		$admin=false;
@endphp
<div class="container">
	<div class="row justify-content-center"> 
		<div class="card col-md-6">
			<h5 class="card-header info-color white-text text-center py-4">
				<strong>{{$admin? 'Admin '.__('Login'):__('Login') }}</strong>
			</h5>
			<br>
			@if(isset($verify))
			<div class="alert alert-success">
				verification success. Please login
			</div>
			@endif
			<!--Card content-->
			<div class="card-body px-lg-5 pt-0">
				@if(session('error'))
					<div class="alert alert-danger">
						{{session('error')}}
					</div>  
				@endif
				<!-- Form -->
				<form method='POST' class="text-center" style="color: #757575;" action="{{ $action }}">
					@csrf
					<!-- Email -->
					<div class="md-form">
						<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{$email??old('email')}}" required autocomplete="email" autofocus placeholder="{{ __('E-Mail Address') }}">
						
						<span id="email-error" class="invalid-feedback" role="alert"  display="{{$errors->first('email')?'show':'hidden'}}">
							<strong>{{ $message??'' }}</strong>
						</span>
						
					</div>
					<!-- Password -->
					<div class="md-form">
						<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="{{ __('Password') }}">
						
							<span id="password-error" class="invalid-feedback" display="{{$errors->first('password')?'show':'hidden'}}" role="alert">
								<strong>{{ $message??'' }}</strong>
							</span>
						
					</div>
					@if(isset($error))
					<div class="alert alert-danger" role="alert">
						invalid {{$error}}
					</div>
					@endif
					<div class="d-flex justify-content-around">
						<div>
							<!-- Remember me -->
							<div class="form-check">
							<input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
					
								<label class="form-check-label" for="remember">Remember me</label>
							</div>
						</div>
						<div>
							<!-- Forgot password -->
							@if (Route::has('password.request'))
								<a href="{{ route('password.request') }}">Forgot password?</a>
							@endif
						</div>
					</div>
					<!-- Sign in button -->
					<button id="login-btn" class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">
					{{ __('Login') }}
						</button>
					@if(!$admin)
						<!-- Register -->
						<h5>Not a member?
							<a href="{{$register_route}}">Register</a>
						</h5>
						<!-- Social login -->
						<h4>sign in with:
							<a href="{{route('google-login')}}" type="button" class="btn-floating btn-fb btn-sm">
								<img src='https://image.flaticon.com/teams/slug/google.jpg' width="50px" height="50px">
							</a>
						</h4>
						<h4>
							OR 
						</h4>
						<button class="btn btn-rounded btn-block wave-effect z-depth-0 btn-outline-warning text-black">
							<a href="/guest">Login as guest</a>
						</button>
					@endif
				</form>
				<!-- Form -->
			</div>
		</div>
	</div>
</div>
@endsection
