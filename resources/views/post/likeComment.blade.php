@php
    $comments=count($article->comments()->where('is_published',1)->get());
    $likes=count($article->likes);
    $iliked=$article->likes()->where('user_id',Auth::id())->get()->toArray();
    $iliked=count($iliked)>0?true:false;
@endphp
<div class="row">
    <div class="mr-auto ml-2">
        <div>
            <h5> <span class='like'>{{$likes}}</span> {{ $likes> 1 ? " Likes" :" Like" }}  </h5> 
        </div>
        <div>
            <i  data-target="{{$iliked}}" class="{{$iliked?'fas red-text':'far'}} fa-heart fa-3x like-heart" onclick="likepressed({{$article->id}},this)"></i>
        </div>
        <span class="like-error" style="displat:none"></span>
    </div>
    <div class="ml-auto mr-1">
        <div>
            <h5> <span class='comment'>{{$comments}}</span> {{$comments > 1 ? " Comments" :" Comment" }}  </h5> 
        </div>
        <div>
            <a href="{{route('article',$article->slug)}}#viewComments">
                <i class="far fa-comment fa-3x" ></i>
            </a>
        </div>
        <span class="comment-error" style="displat:none"></span>
    </div>
</div>