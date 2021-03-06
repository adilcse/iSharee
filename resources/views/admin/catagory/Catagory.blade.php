@extends('layouts.app')

@section('content')
@php
    $value=isset($value)?$value:'';
    $id=isset($id)?$id:0;
    $action=$mode=='Add'?'/admin/catagory/add':'/admin/catagory/edit';
@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Catagory card -->
            <div class="card">
            <h5 class="card-header info-color white-text text-center py-4">
                <strong>{{$mode}} Catagory</strong>
            </h5>
            <!-- add item link if it is in edit mode -->
            @if($mode=='Edit')
                <h6 class='col-md-4 text-left'>
                    <a href='/admin/catagory/add'>Add Items</a>
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
                                <input type="text" id="catagory" name="catagory" class="form-control"  placeholder="{{$mode}} catagory" value="{{$value}}">
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
                        Catagories:
                    </h2>
                    <br/>
                    <div class="col-md-6">
                        <ul>
                            @foreach($catagories as $catagory)
                            <li>
                                <div class="row">
                                    <div class="col text-left">
                                            {{$catagory->name}}
                                    </div>
                                    <div class="col">
                                        <a href='/admin/catagory/edit/{{$catagory->id}}'> <i class="far fa-edit"></i> </a>
                                        &ensp; 
                                        <a href='/admin/catagory/delete/{{$catagory->id}}'> <i class="fas fa-trash-alt"></i> </a>
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
