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
                <a href='/home/catagory/{{$cat->id}}'>
                <span class="btn btn-outline-secondary btn-sm"> <strong>{{$cat->name}}</strong> </span>
                </a>
                @endforeach
            </div>
                <span class="text">
                {{$article->body}}
                </span>
            </div>
            <!-- Button -->
            <a href="{{url('/article/'.$article->id)}}" class="btn btn-primary activator waves-effect mr-4">Read full article</a>
            @if (Gate::forUser(Auth::user())->allows('update-post', $article)) 
           <a class="btn btn-warning mr-2" href="{{url('/article/edit/'.$article->id)}}"> Edit</a>
           <buttun class="btn btn-danger" data-toggle="modal" data-target="#confirmation-{{$article->id}}"> Delete</button>

<!-- Modal -->
            <div class="modal fade" id="confirmation-{{$article->id}}" tabindex="-1" role="dialog" aria-labelledby="header-{{$article->id}}"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark text-center" id="header-{{$article->id}}">Delete ?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-dark">
                    {{$article->title}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a  class="btn btn-danger" href="/article/delete/{{$article->id}}">Delete</a>
                </div>
                </div>
            </div>
            </div>
        @endif
        </div>
    </div>
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
