@extends('layouts.app')
@push('script')
<script src="{{ asset('js/1.0/admin/articleTable.min.js') }}" defer></script>
@endpush
@section('content')
<!-- admin dashboard -->
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-11">
			<div class="container">
				<!-- Section -->
				<section>
					<h6 class="font-weight-bold text-center grey-text text-uppercase small mb-4">Admin</h6>
					<h3 class="font-weight-bold text-center dark-grey-text pb-2">Dashboard</h3>
					<hr class="w-header my-4">
					@if(session('success'))
						<div class='alert alert-success'>
							{{session('success')}}
						</div>
					@endif
					<div class="row">
						<div class="alert alert-danger" id="articleMsg" style="display:none;"></div>
						<div class="col-lg-12 col-md-12 mb-12">
						<!-- article table -->
						@include('admin.dashboard.articlesTable')
						<!-- user table -->
						@include('admin.dashboard.usersTable')
						<!-- comment table -->
						@include('admin.dashboard.commentsTable')
						</div>
					</div>
				</section>
				<!-- Section -->
			</div>
		</div>
	</div>
</div>
@endsection
