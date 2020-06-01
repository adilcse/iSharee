@extends('layouts.app')

@section('content')
@push('script')
<!-- Scripts -->
	<script src="{{ asset('js/1.0/post/like.min.js') }}" defer></script>
@endpush
@if(Auth::user()->is_admin)
	@push('script')
		<script src="{{ asset('js/1.0/admin/articleTable.min.js') }}" defer></script>
	@endpush
@endif
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-9">
			@if (Gate::forUser(Auth::user())->allows('update-post', $article))
				<div class="row">
					@include('post.deleteModal')
				</div>
			@endif
			<div class="card">
				<!--Card image-->
				@if($article->image_url)
				<div class="view">
					<img src="{{$article->image_url}}" class="card-img-top" alt="photo">
				</div>
				@endif
				<!--Card content-->
				<div class="card-body text-center">
				<!--Title-->
				<h3 class="card-title text-left">{{$article->title}}</h3>
				<h5 class="text-secondary text-left">
					{{(new DateTime($article->created_at))->format('M d, Y')}} by 
					<a class="text-primary" href="{{route('userArticles',$article->user->id)}}">
						{{$article->user->name}}
					</a>
				</h5>
				<h6 class="text-secondary text-left"> {{$article->views}} views</h5>
				<div class="row">
					@foreach($article->catagories as $cat)
						<a href="{{route('category',$cat->slug)}}">
							<span class="btn btn-outline-secondary btn-sm"> <strong>{{$cat->name}}</strong> </span>
						</a>
					@endforeach
				</div>
				@include('post.likeComment')
				<!--Text-->
				<p style="font-size:20px">{{$article->body}}</p>
				<br><hr>
				<div class="container mr-auto" id="viewComments">
					<div class='row'>
						<h3> Comments :</h3>   
					</div>
					@include('post.comments')
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
