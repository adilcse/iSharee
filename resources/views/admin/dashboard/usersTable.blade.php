
<div class="row mt-4" id="article-table">
        <div class="col-12">
    <div class="card" id="user-table">
    <div class="card-header  d-flex justify-content-between align-items-center py-1 primary-color">
                <p class="h5-responsive font-weight-bold mb-0 text-white">User's Details</p>
                <ul class="list-unstyled d-flex align-items-center mb-0 text-white">
                    View:   
                    <select class="custom-select md-form" id="admin-article-view" onChange="changeUserView(this)">
                    <option value="1" {{$userview==1 ? 'selected': '' }}>All</option>
                    <option value="2" {{$userview==2 ? 'selected': '' }}>Active</option>
                    <option value="3" {{$userview==3 ? 'selected': '' }}>Inactive</option>
                    </select>
                </ul>
            </div>


    <div class="card-body text-center px-4">
    <div class="list-group list-group-flush table-responsive text-nowrap">
            <table class="table">
            <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Articles</th>
                <th scope="col">Total views</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
        <tr>
            <td>{{$user->name}}</td>
            <td>{{$user->articles_count}}</td>
            <td scope="row">{{$user->articles()->sum('views')}}</td>
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
    <div class="card-footer white py-3 d-flex justify-content-between">
                {{$users->links()}}
            </div>
    <!-- Panel -->

    </div>
</div>
</div>