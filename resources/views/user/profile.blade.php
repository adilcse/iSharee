@extends('layouts.app')

@section('content')
<div class="container">
@if(session('success'))
			<div class="row alert alert-success">
				{{session('success')}}
				successful
			</div>
@endif
@foreach($errors as $error)
	<div class="row alert alert-danger">
		{{$error}}
	</div>
@endforeach
	<div class="row justify-content-center">
		
		<div class="col-md-9">
			<!-- Card -->
			@foreach($articles as $article)
				@include('post.viewCard')
			@endforeach 
<!-- Card -->
		<nav aria-label="Page navigation text-center">
			<ul class="pagination pg-blue justify-content-center">
			{{$articles->links()}}
			</ul>
		</nav>
	</div>
</div>
</div>

@endsection