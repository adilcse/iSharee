@extends('layouts.app')
<!-- error page to display error messages -->
@section('content')
<div class="container">
    <div class='row justify-content-center'>
        <div class='col-md-8  text-center'>
        @if($message)
            <h2>{{$message}}</h2>
        @else
            <h2>Something went wrong</h2>
        @endif
        <img src="{{asset('image/error.jpg')}}" alt='error page'/>
        </div>
    </div>
</div>
@endsection