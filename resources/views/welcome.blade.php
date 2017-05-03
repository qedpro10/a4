@extends('layouts.master')

@push('head')
    <link href='/css/stocker.css' rel='stylesheet'>
    <link href='/css/welcome.css' rel='stylesheet'>
@endpush

@section('title')
    Day Stocker
@endsection

@section('content')

	<h1>Welcome!</h1>
    <p>Welcome to DayStocker - a personal Stock Watcher for Day Traders.</p>
    <p>To get started <a href='/login'>login</a> or <a href='/register'>register</a>.</p>
    <p>Demo login: jill@harvard.edu | helloworld  or jamal@harvard.edu | helloworld</p>

@endsection
