@extends('layouts.app')

@section('content')
 <div class="container">
    <div class="row justify-content-center"> 
    <div class="card col-md-6">

  <h5 class="card-header info-color white-text text-center py-4">
    <strong>{{ __('Login') }}</strong>
  </h5>

  <!--Card content-->
  <div class="card-body px-lg-5 pt-0">

    <!-- Form -->
    <form method='POST' class="text-center" style="color: #757575;" action="{{ route('login') }}">
    @csrf
      <!-- Email -->
      <div class="md-form">
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('E-Mail Address') }}">

        @error('email')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
      </div>

      <!-- Password -->
    <div class="md-form">
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="{{ __('Password') }}">
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
      </div>

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
      <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">
      {{ __('Login') }}
        </button>

      <!-- Register -->
      <p>Not a member?
        <a href="">Register</a>
      </p>

      <!-- Social login -->
      <h4>sign in with:
      <a type="button" class="btn-floating btn-fb btn-sm">
        <img src='https://image.flaticon.com/teams/slug/google.jpg' width="50px" height="50px">
      </a>
    </h4>
    <h4>
        OR 
    </h4>
    <button class="btn btn-rounded btn-block wave-effect z-depth-0 btn-outline-warning text-black">
        Login as guest
</button>

    </form>
    <!-- Form -->

  </div>

</div>

    </div>
</div>
@endsection
