@extends('layouts.master')

@push('head')
    <link href='/css/stocker.css' rel='stylesheet'>
@endpush

@section('title')
    Day Stocker
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col col-md-12">
            <h5>Welcome to DayStocker - A personal Stock Watcher for Day Traders.</h5>
            <h5>To get started <a href='/login'>login</a> or <a href='/register'>register</a>.</h5>
            <br>
            <h5>Demo login: jill@harvard.edu | helloworld  or jamal@harvard.edu | helloworld</h5>
        </div>
    </div>
</div>
@endsection
