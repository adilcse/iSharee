@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Material add article page -->
            <div class="card">
                <h5 class="card-header {{$status == 'success' ?'success-color':'danger-color'}} white-text text-center py-4">
                    <strong>{{$status == 'success' ?'Payment Success':'Payment Failed'}} </strong>
                </h5>
                <!--Card content-->
                <div class="card-body px-lg-5 pt-0">
                    <ul>
                        <li class = 'h3'>Article title : <a href="{{route('article',$article->slug)}}">{{$article->title}}</a></li>
                        <li class = 'h3'>Payment Amount : {{$payment->amount}}</li>
                        <li class = 'h3'>Article status : {{$article->is_published ? 'published':'not published'}}</li>
                        <li class = 'h3'>Payment receipt : <a target="_blank" href="{{$payment->receipt_url}}"> view here</li>
                    <ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection