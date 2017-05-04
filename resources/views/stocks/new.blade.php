{{-- /resources/views/stocks/new.blade.php --}}
@extends('layouts.master')

@section('title')
    New stock
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



        <label for='company_name'>* Company Name</label>
        <input type='text' name='company_name' id='company_name' value='{{ old('company_name', 'ACME Co.') }}'>

        <label for='logo'>* URL to company logo image</label>
        <input type='text' name='logo' id='logo' value='{{ old('cover', '/images/acme_company.png') }}'>


        <input type='text' name='website' id='website' value='{{ old('website', 'https://en.wikipedia.org/wiki/Acme_Corporation') }}'>




        

        <input class='btn btn-primary' type='submit' value='Add Stock'>
    </form>




@endsection
