{{-- /resources/views/stocks/newSearch.blade.php --}}
@extends('layouts.master')

@section('title')
    New stock search
@endsection

@push('head')
    <link href='/css/stocks.css' rel='stylesheet'>
@endpush


@section('content')
    <h1>Add a new stock</h1>

    <form method='POST' action='/stocks/new'>
        {{ csrf_field() }}

        <small>* Required fields</small>

        <label for='ticker'>* Ticker</label>
        <input type='text' name='ticker' id='ticker' value='{{ old('ticker', 'TEST') }}'>

        {{-- Extracted error code to its own view file --}}
        @include('errors')

        <input class='btn btn-primary' type='submit' value='Search for Stock'>
    </form>




@endsection
