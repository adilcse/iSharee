@extends('layouts.app')

@section('content')
@push('script') 
<!-- Scripts -->
<script src="{{ asset('js/post/add.js') }}" defer></script>
@endpush
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Material add article page -->
            <div class="card">
                <h5 class="card-header info-color white-text text-center py-4">
                    <strong>Add Article</strong>
                </h5>
                <!--Card content-->
                <div class="card-body px-lg-5 pt-0">
                    @if(session('status'))
                    <div class="alert alert-success">
                        {{session('status')}}
                    </div>
                    @endif
                    @foreach($errors->all() as $error)
                    <div class="alert alert-danger">
                        {{$error}}
                    </div>
                    @endforeach
                    <!-- Form -->
                    <form class="text-center" style="color: #757575;" action="{{route('postArticle')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <!-- Name -->
                        <div class="md-form mt-3">
                            <input type="text" id="title" name="title" class="form-control" placeholder="Title">
                            <span class="invalid-feedback" style="display:{{$errors->has('title')?'show':'none'}}" role="alert">
                                <strong>{{ $errors->first('title')??'' }}</strong>
                            </span>
                        </div>
                        <span>Catagory</span>
                        <div>
                            <select id="multiple-selected" class="custom-select mdb-select" name='catagory[]' multiple="multiple">
                                @foreach($catagory as $cat)
                                    <option value="{{$cat->id}}">{{$cat->name}}</option>
                                @endforeach
                            </select>
                        </div>
                            <!-- image upload -->
                        <br/>
                        <div class="row justify-content-center">
                            <div class="file-field">
                                <div class="btn  btn-sm float-left inline">
                                    <span> Image Upload   </span>
                                    <input type="file" name="image" accept="image/*">
                                </div>
                            </div>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="sliderCheck" checked class="form-check-input" id="allow-slider-check">
                            <label class="form-check-label" for="allow-slider-check">Allow image to view as slider?</label>
                        </div>
                        <!--Body-->
                        <div class="md-form row">
                            <textarea id="body" name="body" class="form-control md-textarea" rows="3" placeholder="Description"></textarea>
                            <span class="invalid-feedback" style="display:{{$errors->has('body')?'show':'none'}}" role="alert">
                                <strong>{{ $errors->first('body')??'' }}</strong>
                            </span>
                        </div>
                        <!-- Send button -->
                        <div id="error" class="alert alert-danger mb-3" style="display:none"></div>
                        <button class="btn btn-outline-info btn-rounded btn-block z-depth-0 my-4 waves-effect" type="submit">Post</button>
                    </form>
                    <!-- Form -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
