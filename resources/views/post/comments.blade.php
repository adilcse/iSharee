@push('script') 
<!-- Scripts -->
<script src="{{ asset('js/post/comment.min.js') }}" defer></script>
@endpush
<div class = 'row'>
	<div class="col">
		@if(session('comment'))
			<div class="alert alert-info h5">
				{{session('comment')}}
			</div>
		@endif
		<br/>
		@if($article->comments()->where('is_published',1)->count() <1)
				<div id="zeroComments" class="alert alert-info h5">Be the first to comment</div>
		@else
		@foreach($article->comments as $com)
			@if($com->pivot->is_published)
			<div  class = 'userComment row'>
				<div class="col">
					<div class="card card-cascade z-depth-0">
						<div class="card-body card-body-cascade text-left">
						<!-- Title -->
							<div class=" card-title row">
								<h4 class="font-weight-bold">
									<a class="text-primary" href="{{route('userArticles',$com->id)}}">
										{{$com->name}} 
									</a>
									<div class="text-muted h6">
										{{$com->pivot->created_at->format('d M, Y g:i A')}}
									</div>	
								</h4>
								@if(Gate::allows('update-comment',$com))
								<span class="ml-auto">
									<a href="/article/comment/update/{{$com->pivot->id}}?status=0&from=article"><i class="fas fa-trash-alt"></i></a>
								</span>
								@endif
							</div>
							<!-- Text -->
							<p class="card-text">{{$com->pivot->body}}</p>
						</div>
					</div>
				</div>
			</div>
			<br>
			@endif
		@endforeach
		@endif
	</div>
</div>
<div class="row">
	<div class="col">
	<div class="alert" id="commentMsg" style="display:none;">

		</div>
		<form class="md-form" method="POST">
			@csrf
			<input hidden name="id" value="{{$article->id}}">
			<div class="form-group shadow-textarea">
				<textarea row="3" name="comment" placeholder="Comment here" class="md-textarea form-control "></textarea>
			</div>
			<button type="submit" class="btn btn-primary"> Comment </button>
			<button id="commentLoading" class="h4 btn btn-primary disabled" style="display:none;">
				Loading ......
</button>
		</form>
	</div>
</div>



<!-- delete modal -->
	