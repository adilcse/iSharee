
<div class="row mt-4" id="comments-table">
        <div class="col-12">
    <div class="card">
    <div class="card-header  d-flex justify-content-between align-items-center py-1 primary-color">
                <p class="h5-responsive font-weight-bold mb-0 text-white my-3">Guest Comments</p>
            </div>


    <div class="card-body text-center px-4">
    <div class="list-group list-group-flush table-responsive text-nowrap">
            <table class="table">
            <thead>
            <tr>
                <th scope="col">Article Title</th>
                <th scope="col">Published by</th>
                <th scope="col">Comment</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        @if(is_null($comments))
            <h3> no pending comments</h3>
        @else
        <tbody>
            @foreach($comments as $comment)
        <tr>
            <th scope="row"><a class="text-primary" href="{{route('article',$comment->article->id)}}">
            {{strlen($comment->article->title)>30?substr($comment->article->title,0,30)."..." :$comment->article->title }}
            </a></th>
            <td><a class="text-primary" href="{{route('admin.userView',$comment->user->id)}}">{{$comment->user->name}}</a></td>
            <td>{{strlen($comment->body)>30?substr($comment->body,0,30)."..." :$comment->body }}</td>
            <td>
                <button type="button" class="btn btn-success wave-effect" onClick="updateCommentStatus(this,'{{$comment->id}}',1)"> Approve</button>
                <button type="button" class="btn btn-danger wave-effect" onClick="updateCommentStatus(this,'{{$comment->id}}',0)"> Reject</button>
            </td>
        </tr>
        @endforeach
        </tbody>
        @endif
        </table>
    </div>
    </div>
    <div class="card-footer white py-3 d-flex justify-content-between">
            {{$comments->links()}}
        </div>
    <!-- Panel -->

    </div>
</div>
</div>