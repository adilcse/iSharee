@php
    $in=$article->body;
    $article->body= strlen($in) > 200 ? substr($in,0,200)."..." : $in;
@endphp
<!-- view article card -->
<div class="card mb-3" id="card-{{$article->id}}">
    <!-- Card image -->
    @if($article->image_url && $article->allow_image_as_slider)
        <div class="view overlay ">
            <img class="card-img-top" src="{{$article->image_url}}" width='300px' height="500px" alt="{{$article->title.'-image'}}">
        </div>
    @endif
    <!-- Card content -->
    <div class="card-body">
        <!-- Title -->
        <a href="{{route('article',$article->slug)}}" class="text-decoration-none">
            <h3 class="card-title">{{$article->title}}</h3>
        </a>
        <h5 class="text-secondary">Published by:<a class="text-primary" href="{{route('userArticles',$article->user->id)}}">{{$article->user->name}}</a></h5>
        <h6 class="text-secondary"> {{$article->views}} views</h5>
        <!-- Text -->
        <div class="card-text">
            <div class="row">
                @foreach($article->catagories as $cat)
                <a href='{{route("catagory",$cat->slug)}}'>
                <span class="btn btn-outline-secondary btn-sm"> <strong>{{$cat->name}}</strong> </span>
                </a>
                @endforeach
            </div>
            <span class="text">
            {{$article->body}}
            </span>
        </div>
        <!-- Button -->
        <div class="row">
            <a href="{{route('article',$article->slug)}}" class="btn btn-primary activator waves-effect mr-4">Read full article</a>
            @if (Gate::forUser(Auth::user())->allows('update-post', $article)) 
                    @include('post.deleteModal')
            @endif
        </div>
        @include('post.likeComment')
    </div>
</div>