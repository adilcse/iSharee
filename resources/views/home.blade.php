@extends('layouts.app')

@section('content')
<div class="container">
@if(session('success'))
            <div class="row alert alert-success">
                {{session('success')}}
                successful
            </div>
        @endif
    <div class="row justify-content-center">
        
        <div class="col-md-9">
            @if(isset($catagory))
                <h3>Catagory:{{$catagory->name}}</h3>
            @endif
            <!-- Card -->
            @foreach($articles as $article)
                @include('post.viewCard')
                @yield('viewCard')
            @endforeach 
<!-- Card -->
<nav aria-label="Page navigation text-center">
  <ul class="pagination pg-blue justify-content-center">
   {{$articles->links()}}
  </ul>
</nav>
    </div>
</div>
@endsection
