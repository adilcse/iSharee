<!-- admin user details table -->
<div class="row" id="article-table">
	<div class="col-12">
		<div class="card card-list">
			<div class="card-header primary-color text-white d-flex justify-content-between align-items-center py-1">
				<p class="h5-responsive font-weight-bold mb-0">Articles</p>
				<ul class="list-unstyled d-flex align-items-center mb-0">
					View:   
					<select class="custom-select md-form" id="admin-article-view" onChange="changeArticeView(this)">
					<option value="1" {{$view==1 ? 'selected': '' }}>All</option>
					<option value="2" {{$view==2 ? 'selected': '' }}>Pending</option>
					<option value="3" {{$view==3 ? 'selected': '' }}>Published</option>
					</select>
				</ul>
			</div>
			<div class="card-body table-responsive text-nowrap text-center">
				@if(!$articles->items())
					<h3> No Articles</h3>
				@else
				<!-- dispay article table only of articles present -->
					<table class="table table-hover">
						<thead>
							<tr>
								<th scope="col">Title</th>
								<th scope="col">Likes</th>
								<th scope="col">Published By</th>
								<th scope="col">Total Views</th>
								<th scope="col">Status</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($articles as $article)
								<tr>
									<th scope="row"><a class="text-primary" href="{{route('article',$article->slug)}}">
										<!-- trim lengthy article name -->
										{{strlen($article->title)>30?substr($article->title,0,30)."..." :$article->title }}</a>
									</th>
									<td>{{$article->likes_count}}</a></td>
									<td ><a href="{{route('admin.userView',$article->user->id)}}" class="text-primary">{{$article->user->name}}</a></td>
									<td >{{$article->views}}</td>
									<td>
										<span class="badge {{$article->is_published ? 'badge-success' : 'badge-warning'}}">
											<select class="custom-select" id="admin-article-status" onChange="articleStatusChanged({{$article->id}},this)">
												<option value="0"  {{$article->is_published === 0 ? 'selected' : ''}}>Pending</option>
												<option value="1" {{$article->is_published ? 'selected' : ''}}>Published</option>
											</select>
										</span>
										<span data-target="{{route('admin.article.delete',$article->id)}}" onClick="deleteArticle(this,'{{$article->title}}')">
											<i class=" ml-4 fa-2x fas fa-trash-alt"></i>
										</span>
									</td>
								<tr>
							@endforeach
						</tbody>
					</table>
				@endif
			</div>
			<div class="card-footer white py-3 d-flex justify-content-between">
				{{$articles->links()}}
			</div>
		</div>
	</div>
</div>