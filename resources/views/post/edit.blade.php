@extends('layouts.app')
@push('script')
<!-- Scripts -->
    <script src="{{ asset('js/post/edit.js') }}" defer></script>
@endpush
@section('content')
@php
    $catids=[];
    foreach($article->catagories as $cat){
        array_push($catids,$cat->id);  
    }
    $rows=strlen($article->body)/75;
    $rows=$rows>10?10:$rows;
@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <h5 class="card-header info-color white-text text-center py-4">
                    <strong>Edit Article</strong>
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
                    <form class="text-center" style="color: #757575;" action="{{route('editArticle')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input hidden name="id" value="{{$article->id}}">
                        <!-- Name -->
                        <div class="md-form mt-3">
                            <input type="text" id="title" name="title" class="form-control" placeholder="Title" value="{{$article->title}}">
                        </div>
                        <div>
                            <span>Catagory</span>
                            <div>
                            <select id="multiple-selected" class="custom-select mdb-select" name='catagory[]' multiple="multiple">
                                @foreach($catagory as $cat)
                                    <option value="{{$cat->id}}"  {{(in_array($cat->id,$catids))? 'selected' : ''}}> 
                                        {{$cat->name}}
                                    </option>
                                @endforeach
                            </select>
                            <br/>
                            <img src="{{$article->image_url}}" id="img" class="img-fluid"   @if(!$article->image_url)style="display:none" @endif>
                            <div class="row justify-content-center">
                                <div class="file-field">
                                    <div class="btn  btn-sm float-left inline">
                                        <span> Image Upload   </span>
                                        <input type="file" name="image" accept="image/*" id="image">
                                    </div>
                                    <br>
                                    <div class="form-check">
                                        <input type="checkbox" name="sliderCheck" {{$article->allow_image_as_slider ? 'checked' : ''}} class="form-check-input" id="allow-slider-check">
                                        <label class="form-check-label" for="allow-slider-check">Allow image to view as slider?</label>
                                    </div>
                                </div>
                            </div>
                        <!--Message-->
                        <div class="md-form row">
                            <textarea type="text" id="body" name="body" class="form-control md-textarea" rows="{{$rows}}" placeholder="Description" >{{trim($article->body)}}</textarea>                       </textarea>
                        </div>
                        <!-- Send button -->
                        <button class="btn btn-outline-info btn-rounded btn-block z-depth-0 my-4 waves-effect" type="submit">Update</button>
                    </form>
                    <!-- Form -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
