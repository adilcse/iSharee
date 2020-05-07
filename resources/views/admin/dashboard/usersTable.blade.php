<div class="card">

<div class="card-header white-text primary-color">
<p class="h5-responsive font-weight-bold mb-0">User Details</p>  
  </div>


<div class="card-body text-center px-4">
  <div class="list-group list-group-flush">
        <table class="table">
        <thead>
        <tr>
            <th scope="col">User ID</th>
            <th scope="col">Name</th>
            <th scope="col">Articles</th>
            <th scope="col">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
      <tr>
        <th scope="row"><a class="text-primary">{{$user->id}}</a></th>
        <td>{{$user->name}}</td>
        <td>{{$user->articles_count}}</td>
        <td><span class="badge {{$user->is_active ? 'badge-success' : 'badge-danger'}}">
        <select class="custom-select" id="admin-article-status" onChange="userStatusChanged({{$user->id}},this)">
            <option value="1"   {{$user->is_active ? 'selected' : ''}}>Active</option>
            <option value="0" {{!$user->is_active ? 'selected' : ''}}>Inactive</option>
        </select>
        </span></td>
      </tr>
      @endforeach
      </tbody>
      </table>
  </div>
</div>

</div>
<!-- Panel -->

</div>