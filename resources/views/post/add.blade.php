@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
           <!-- Material form contact -->
<div class="card">

<h5 class="card-header info-color white-text text-center py-4">
    <strong>Add Article</strong>
</h5>

<!--Card content-->
<div class="card-body px-lg-5 pt-0">

    <!-- Form -->
    <form class="text-center" style="color: #757575;" action="{{rourte('postArticle')}}">

        <!-- Name -->
        <div class="md-form mt-3">
            <input type="text" id="title" name="title" class="form-control" placeholder="Title">
        </div>


        <!-- Catagory -->
        <div class="    ">
        <span>Catagory</span>
        <select class="mdb-select custom-select">
            <option value="" disabled>Choose option</option>
            @foreach($catagory as $cat)
            <option value="{{$cat->id}}">{{$cat->name}}</option>
            @endforeach
        </select>
        <div>

        <!--Message-->
        <div class="md-form row">
            <textarea id="materialContactFormMessage" class="form-control md-textarea" rows="3" placeholder="Description"></textarea>
        </div>

        <!-- Send button -->
        <button class="btn btn-outline-info btn-rounded btn-block z-depth-0 my-4 waves-effect" type="submit">Post</button>

    </form>
    <!-- Form -->

</div>

</div>
<!-- Material form contact -->
        </div>
    </div>
</div>
@endsection
