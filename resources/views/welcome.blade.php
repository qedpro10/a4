@extends('layouts.master')

@push('head')
    <link href='/css/stocker.css' rel='stylesheet'>
    <link href='/css/welcome.css' rel='stylesheet'>
@endpush

@section('title')
    Day Stocker
@endsection

@section('content')

    <p>Welcome to DayStocker - A personal Stock Watcher for Day Traders.</p>
    <p>To get started <a href='/login'>login</a> or <a href='/register'>register</a>.</p>
    <br>
    <p>Demo login: jill@harvard.edu | helloworld  or jamal@harvard.edu | helloworld</p>

@endsection
