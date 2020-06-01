@extends('layouts.app')

@section('content')
@php
    $value=isset($value)?$value:'';
    $id=isset($id)?$id:0;
    $action=$mode=='Add'?'/admin/category/add':'/admin/category/edit';
@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Category card -->
            <div class="card">
            <h5 class="card-header info-color white-text text-center py-4">
                <strong>{{$mode}} Category</strong>
            </h5>
            <!-- add item link if it is in edit mode -->
            @if($mode=='Edit')
                <h6 class='col-md-4 text-left'>
                    <a href='/admin/category/add'>Add Items</a>
                </h6>
                @endif
            <!--Card content-->
            <div class="card-body px-lg-5 pt-0">
                <!-- Form -->
                <form class="text-center" style="color: #757575;" action="{{$action}}" method="POST">
                    @csrf
                    <br>
                    @if(isset($invalid))
                        <div class="alert alert-danger">
                            Invalid id 
                        </div>
                    @endif
                    @if(count($errors)>0)
                        @foreach($errors->all() as $error)
                        <!-- error message -->
                            <div class="alert alert-danger">
                            {{$error}}
                            </div>
                        @endforeach
                    @endif
                    @if(session('success'))
                    <!-- success message -->
                    <div class="alert alert-success">
                        {{session('success')}}
                    </div>
                    @endif
                    <div class="md-form mt-3">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" id="category" name="category" class="form-control"  placeholder="{{$mode}} category" value="{{$value}}">
                                <input hidden type="text" name="id" value="{{$id}}" />
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-outline-info btn-rounded z-depth-0 btn-block waves-effect" type="submit">
                                    {{$mode}}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <h2>
                        Categories:
                    </h2>
                    <br/>
                    <div class="col-md-6">
                        <ul>
                            @foreach($catagories as $category)
                            <li>
                                <div class="row">
                                    <div class="col text-left">
                                            {{$category->name}}
                                    </div>
                                    <div class="col">
                                        <a href='/admin/category/edit/{{$category->id}}'> <i class="far fa-edit"></i> </a>
                                        &ensp; 
                                        <a href='/admin/category/delete/{{$category->id}}'> <i class="fas fa-trash-alt"></i> </a>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
