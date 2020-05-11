<!-- Edit delete article button and modal -->
<div class="col">
	<div class="row">
		<a class="btn btn-warning mr-2" href="{{url('/article/edit/'.$article->slug)}}"> Edit</a>
		<button class="btn btn-danger" data-toggle="modal" data-target="#confirmation-{{$article->id}}"> Delete</button>
		@if(isset($status) && !Auth::user()->is_admin)
			<div class="h3 bg-warning align-self-center">
				Status : {{$status}}
			</div>
		@endif
		<div class="ml-auto h4">
			@if(Auth::user()->is_admin)
				Change Status:
				<span class="d-inline-block">
					<select class="custom-select" id="admin-article-status" onChange="articleStatusChanged({{$article->id}},this)">
						<option value="0"  {{0 == $article->is_published  ? 'selected' : ''}}>Pending</option>
						<option value="1" {{$article->is_published ? 'selected' : ''}}>Published</option>
					</select>
				</span>
			@elseif(isset($myArticle))
				<div>
					Status : <span>{{$article->is_published ? 'Published' : 'Pending'}}</span>
				</div>
			@endif
		</div>
	</div>
<!-- Modal -->
	<div class="modal fade" id="confirmation-{{$article->id}}" tabindex="-1" role="dialog" aria-labelledby="header-{{$article->id}}" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title text-dark text-center" id="header-{{$article->id}}">Delete ?</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body text-dark">
					{{$article->title}}
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<a  class="btn btn-danger" href="/article/delete/{{$article->slug}}">Delete</a>
				</div>
			</div>
		</div>
	</div>
</div>