<html lang="en">
<!-- notify user that comment is added for its article -->
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>New Comment</title>
    </head>
    <body>
        <h1> A new Comment is added </h1>  
        <h2> For Article : <a href="{{route('article',$comment->article->id)}}">{{$comment->article->title}}</a></h2>
        <h3> By : <a href="{{route('admin.userView',$comment->user->id)}}">{{$comment->user->name}} </a></h3>
        <h4>Status : {{$comment->is_published ? 'Published' : 'Pending'}} </h4>
        <p>{{$comment->body}}</p>
    </body>
</html>