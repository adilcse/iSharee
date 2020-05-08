@extends('layouts.app')
@push('script')
<script src="{{ asset('js/admin/articleTable.js') }}" defer></script>
@endpush
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
        <div class="container">
       
  <!-- Section -->
  <section>
    <h6 class="font-weight-bold text-center grey-text text-uppercase small mb-4">Admin</h6>
    <h3 class="font-weight-bold text-center dark-grey-text pb-2">Dashboard</h3>
    <hr class="w-header my-4">

    <div class="row">

      <!-- Grid column -->
      <div class="col-lg-12 col-md-12 mb-12">

        <!-- Panel -->
     @include('admin.dashboard.articleTable')
      <!-- Grid column -->

  @include('admin.dashboard.usersTable')
 

    </div>

  </section>
  <!-- Section -->

</div>
        </div>
    </div>
</div>
@endsection
