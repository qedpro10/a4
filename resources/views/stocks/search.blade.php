{{-- /resources/views/stocks/search.blade.php --}}
@extends('layouts.master')

@section('title')
    Search
@endsection

@push('head')
    <link href='/css/stocks.css' rel='stylesheet'>
@endpush


@section('content')
    <h1>Search for a stock</h1>

    <form method='POST' action='/stocks/search'>
        {{ csrf_field() }}
        <label for='ticker'>* Ticker</label>
        <input type='text' name='ticker' id='ticker' value='{{ old('ticker') }}'>
        <br>
        <fieldset class='radios'>
            <label>Search Type: </label>
            <label><input type='radio' name='searchType' value='local' @if(old('searchType') == 'local' || old('searchType') == '') CHECKED @endif>Local</label>
            <label><input type='radio' name='searchType' value='stockEx'  @if(old('searchType') == 'stockEx') CHECKED @endif>Stock Exchanges</label>
        </fieldset>
        <br>
        <input type='checkbox' name='exact' id='exact' value='{{ old('exact') ? 'CHECKED' : '' }}' >
        <label>exact match</label>
        <br>
        <br>

        {{-- Extracted error code to its own view file --}}
        @include('errors')

        <input class='btn btn-primary' type='submit' value='Search'>
    </form>

@endsection