{{-- /resources/views/stocks/edit.blade.php --}}
@extends('layouts.master')

@section('title')
    Edit Stock: {{ $stock->title }}
@endsection

@push('head')
    <link href='/css/stocks.css' rel='stylesheet'>
@endpush

@section('content')
    <h1>Edit</h1>
    <h2>{{ $stock->title }}</h2>

    <form method='POST' action='/stocks/edit'>
        {{ csrf_field() }}

        <p>* Required fields</p>

        <input type='hidden' name='id' value='{{$stock->id}}'>

        <label for='ticker'>* Ticker</label>
        <input type='text' name='ticker' id='ticker' value='{{ old('ticker', $stock->ticker) }}'>

        <label for='exchange_id'>* Stock Exchange:</label>
        <select id='exchange_id' name='exchange_id'>
            <option value='0'>Choose</option>
            @foreach($exchangeForDropdown as $exchange_id => $exchangeName)
                <option value='{{ $exchange_id }}' {{ ($stock->exchange_id == $exchange_id) ? 'SELECTED' : '' }}>
                    {{ $exchangeName }}
                </option>
            @endforeach
        </select>

        <label for='company_name'>* Company Name</label>
        <input type='text' name='company_name' id='company_name' value='{{ old('company_name', $stock->company_name) }}'>

        <label for='logo'>* URL to a logo image</label>
        <input type='text' name='logo' id='logo' value='{{ old('logo', $stock->logo) }}'>

        <label for='website'>* Company Investor Website</label>
        <input type='text' name='website' id='website' value='{{ old('website', $stock->website) }}'>


        {{-- Extracted error code to its own view file --}}
        @include('errors')

        <br><input class='btn btn-primary' type='submit' value='Save changes'><br><br>

        

    </form>



@endsection
