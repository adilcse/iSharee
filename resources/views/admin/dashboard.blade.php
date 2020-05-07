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

      <!-- Grid column -->
      <div class="container my-5">

  
  <!-- Section: Block Content -->
  <section>
  @include('admin.dashboard.usersTable')
 

  </section>
  <!-- Section: Block Content -->

  
</div>
      <!-- Grid column -->

      <!-- Grid column -->
      <div class="col-lg-4 col-md-6 mb-4">

        <!-- Panel -->
        <div class="card">

          <div class="card-header white-text primary-color">
            Statistics
          </div>

          <div class="card-body text-center px-4">
            <div class="list-group list-group-flush">
              <a href="#" class="list-group-item d-flex justify-content-between dark-grey-text">Cras justo odio
                <i class="fas fa-wrench ml-auto" data-toggle="tooltip" data-placement="top" title="" data-original-title="Click to fix"></i></a>
              <a href="#" class="list-group-item d-flex justify-content-between dark-grey-text">Dapibus ac facilisi
                <i class="fas fa-wrench ml-auto" data-toggle="tooltip" data-placement="top" title="" data-original-title="Click to fix"></i></a>
              <a href="#" class="list-group-item d-flex justify-content-between dark-grey-text">Morbi leo risus
                <i class="fas fa-wrench ml-auto" data-toggle="tooltip" data-placement="top" title="" data-original-title="Click to fix"></i></a>
              <a href="#" class="list-group-item d-flex justify-content-between dark-grey-text">Porta ac consectet
                <i class="fas fa-wrench ml-auto" data-toggle="tooltip" data-placement="top" title="" data-original-title="Click to fix"></i></a>
              <a href="#" class="list-group-item d-flex justify-content-between dark-grey-text">Vestibulum at eros 
                <i class="fas fa-wrench ml-auto" data-toggle="tooltip" data-placement="top" title="" data-original-title="Click to fix"></i></a>
            </div>
          </div>

        </div>
        <!-- Panel -->

      </div>
      <!-- Grid column -->

    </div>

  </section>
  <!-- Section -->

</div>
        </div>
    </div>
</div>
@endsection
