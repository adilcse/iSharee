<div class = 'row'>
		   <div class="col">
			   @if(session('comment'))
			   {{session('comment')}}
			   @endif
	   @if(count($article->comments) <1)
			Be the first to comment
	   @else
	   @foreach($article->comments as $com)
		@if($com->pivot->is_published)
		<div class = 'row'>
				<div class="col">
					<div class="card card-cascade z-depth-0">

		<!-- Card content -->
		<div class="card-body card-body-cascade text-left">
		<!-- Title -->
		<h4 class="font-weight-bold card-title">{{$com->name}}</h4>
		<!-- Text -->
		<p class="card-text">{{$com->pivot->body}}</p>
		</div>
		</div>
</div>
		<!-- Card Narrower -->
				</div>
				<br>
			@endif
	   @endforeach
	   @endif
</div>
		</div>
	   <div class="row">
		   <div class="col">
			<form class="md-form" method="POST" action="/article/comment">
			@csrf
			<input hidden name="id" value="{{$article->id}}">
			<div class="form-group shadow-textarea">
				<textarea row="3" name="comment" placeholder="Comment here" class="md-textarea form-control "></textarea>
			</div>
			<button type="submit" class="btn btn-primary"> Comment </button>
			</form>
			</div>
		</div>
	</div>
</div>
</div>
	
</div>
<!--/.Card-->

<!-- Material form contact -->
		</div>
	</div>