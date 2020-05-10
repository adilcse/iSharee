<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Article</title>
</head>
<body>
    <h1> A new Article is Published </h1>  
    <h2> Title : <a href="{{route('article',$article->id)}}">{{$article->title}}</a></h2>
    <h3> By : <a href="{{route('admin.userView',$user->id)}}">{{$user->name}} </a></h3>
    <h4>Article Status : {{$article->is_published ? 'Published' : 'Pending'}} </h4>
    <p>{{$article->body}}</p>
</body>
</html>