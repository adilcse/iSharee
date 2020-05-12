@extends('layouts.app')

@section('content')
@push('script')
<!-- Scripts -->
	<script src="{{ asset('js/post/add.js') }}" defer></script>
	<script src="{{ asset('js/post/like.js') }}" defer></script>
	<script src="{{ asset('js/admin/articleTable.js') }}" defer></script>
@endpush
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
				<h3 class="card-title">{{$article->title}}</h3>
				<h6 class="text-secondary text-left"> {{$article->views}} views</h5>
				<div class="row">
					@foreach($article->catagories as $cat)
						<a href="{{route('catagory',$cat->slug)}}">
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
