@extends('layouts.app')

@section('content')
<!-- add scripts -->
@push('script')
    <script src="{{ asset('js/post/like.min.js') }}" defer></script>
@endpush
@if(Auth::user()->is_admin)
	@push('script')
		<script src="{{ asset('js/admin/articleTable.min.js') }}" defer></script>
	@endpush
@endif

<div class="container">
    @if(isset($message))
        <div class="row alert alert-danger">
            {{$message}}
        </div>
    @endif
    <!-- dispay success messages -->
    @if(session('success'))
        <div class="row alert alert-success">
            {{session('success')}}
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-9">
            @if(isset($category))
                <h3>Category:{{$category->name}}</h3>
            @elseif(isset($name))
            <h3>User name :{{$name}}</h3>
            @endif
            @if(!is_null($articles) && !$articles->isEmpty())
                <!-- Card -->
                @foreach($articles as $article)
                    @include('post.viewCard')
                @endforeach 
                <!-- Card -->
                <!-- navigate page links -->
                <nav aria-label="Page navigation text-center">
                    <ul class="pagination pg-blue justify-content-center">
                    {{$articles->links()}}
                    </ul>
                </nav>
                <!-- no article message -->
            @else
                <div class="container text-center h3">
                    No article to display
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
