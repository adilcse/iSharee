
<div class="row">
      <div class="col-12">
      	<div class="card card-list">
          <div class="card-header white d-flex justify-content-between align-items-center py-1">
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
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">Article ID</th>
                  <th scope="col">Title</th>
                  <th scope="col">Status</th>
                  <th scope="col">Published By</th>
                  <th scope="col">Total Views</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($articles as $article)
                    <tr>
                        <th scope="row"><a class="text-primary" href="{{route('article',$article->id)}}">{{$article->id}}</a></th>
                        <td>{{strlen($article->title)>30?substr($article->title,0,30)."..." :$article->title }}</td>
                        <td><span class="badge {{$article->is_published ? 'badge-success' : 'badge-warning'}}">
                            <select class="custom-select" id="admin-article-status" onChange="articleStatusChanged({{$article->id}},this)">
                            <option value="0"  {{$article->is_published === 0 ? 'selected' : ''}}>Pending</option>
                            <option value="1" {{$article->is_published ? 'selected' : ''}}>Published</option>
                            </select>
                            </span></td>
                        <td >{{$article->user->name}}</td>
                        <td >{{$article->views}}</td>
                    <tr>
                @endforeach
                </tbody>
            </table>
          </div>
          <div class="card-footer white py-3 d-flex justify-content-between">
            {{$articles->links()}}
          </div>
        </div>
      </div>
    </div>