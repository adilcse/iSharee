@extends('layouts.app')

@push('script')
    <script src="{{ asset('js/admin/articleTable.min.js') }}" defer></script>
@endpush

@section('content')
@php
    $action=url('/admin/user/update');
@endphp
<!-- user profile page -->
<div class="container">
    @if(session('status'))
    <!-- show success message -->
        <div class="row alert alert-success">
            {{session('status')}}
        </div>
    @endif
    @foreach($errors->all() as $error)
    <!-- displat errors -->
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
                <div class="avatar mx-auto white">
                    <img src="{{asset('image/article.png')}}" width="200px" height="200px" class="rounded-circle" alt="avatar">
                </div>
                <h4 class="text-center"> user profile </h4>
                <!-- Content -->
                <div class="card-body">
                    <h3>User status:
                        <span class="badge {{$profile->is_active ? 'badge-success' : 'badge-danger'}}">
                            <select class="custom-select" id="admin-user-status" onChange="userStatusChanged({{$profile->id}},this)">
                                <option value="1"   {{$profile->is_active ? 'selected' : ''}}>Active</option>
                                <option value="0" {{!$profile->is_active ? 'selected' : ''}}>Inactive</option>
                            </select>
                        </span>
                    </h3>
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
                                    <input type="text" id="mobile" class="form-control @error('mobile') is-invalid @enderror" name="mobile" requirded autocomolete="number" placeholder="{{ __('Mobile') }}" value="{{$profile->mobile}}">
                                    <input type="radio" name="mobileVerify" value="1" {{$profile->is_mobile_verified ? 'checked' : ''}}> Verifyed</input>
                                    <input type="radio" name="mobileVerify" value="0" {{!$profile->is_mobile_verified ? 'checked' : ''}}> Unverified</input>
                                    @error('mobile')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <!-- E-mail -->
                        <div class="md-form mt-0">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $profile->email }}" required autocomplete="email" placeholder="{{__('Email')}}">
                            <input type="radio" name="emailVerify" value="1" {{$profile->is_email_verified ? 'checked' : ''}}> Verifyed</input>
                            <input type="radio" name="emailVerify" value="0" {{!$profile->is_email_verified ? 'checked' : ''}}> Unverified</input>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">
                            UPDATE
                        </button>
                    </form>
                <!-- Form -->
			    </div>
		    </div>
	    </div>
    </div>
</div>

@endsection