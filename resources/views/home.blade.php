@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        
        <div class="col-md-9">
            <!-- Card -->
            @foreach($articles as $article)
            <div class="card mb-3">
                @php
                $in=$article->body;
                $article->body= strlen($in) > 200 ? substr($in,0,200)."..." : $in;

                @endphp
            <!-- Card image -->
            @if($article->image_url)
            <div class="view overlay">
                
            <img class="card-img-top" src="{{$article->image_url}}" width='400px' height="500px" alt="Card image cap">
            <a href="#">
                <div class="mask rgba-white-slight"></div>
            </a>
            </div>
            @endif
            <!-- Card content -->
            <div class="card-body">

            <!-- Title -->
            <h4 class="card-title">{{$article->title}}</h4>
            <!-- Text -->
           
            <div class="card-text">
            <div class="row">
                @foreach($article->catagories as $cat)
                <span class="btn btn-outline-secondary btn-sm"> <strong>{{$cat->name}}</strong> </span>
                @endforeach
            </div>
                <span class="text">
                {{$article->body}}
                </span>
            </div>
            <!-- Button -->
            <a href="{{url('/article/'.$article->id)}}" class="btn btn-primary activator waves-effect mr-4">Read full article</a>
            </div>
            </div>
        @endforeach
<!-- Card -->
  
    </div>
</div>
@endsection
