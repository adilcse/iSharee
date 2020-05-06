@section('deleteModal')
<a class="btn btn-warning mr-2" href="{{url('/article/edit/'.$article->id)}}"> Edit</a>
<buttun class="btn btn-danger" data-toggle="modal" data-target="#confirmation-{{$article->id}}"> Delete</button>

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
				<a  class="btn btn-danger" href="/article/delete/{{$article->id}}">Delete</a>
			</div>
		</div>
	</div>
</div>
@endsection