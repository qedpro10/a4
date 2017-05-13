{{-- /resources/views/stocks/search.blade.php --}}
@extends('layouts.master')

@section('title')
    Search
@endsection

@push('head')
    <link href='/css/stocks.css' rel='stylesheet'>
@endpush


@section('content')
    <div class="container" id="content">
        <div class="row">
            <div class="col col-md-12">
                <h1>Search for a stock</h1>
            </div>
        </div>
    </div>
    <div class="row" id="content">
        <div class="col col-md-4">
            <form method='POST' action='/stocks/search'>
                {{ csrf_field() }}
                <label for='ticker'>* Ticker</label>
                <input type='text' name='ticker' id='ticker' value='{{ $searchTicker or '' }}'>
                <br>
                <fieldset>
                    <label>Search Type: </label>
                    <label><input type='radio' name='searchType' value='local'
                        @if( old('searchType') == 'local' || $searchType == 'local' || $searchType  == '') CHECKED @endif>Local</label>
                    <label><input type='radio' name='searchType' value='stockEx'
                        @if( old('searchType') || $searchType == 'stockEx') CHECKED @endif>Stock Exchanges</label>
                </fieldset>
                <br>
                <input type='checkbox' name='exactMatch' {{(old('exactMatch') || ($exactMatch)) ? 'CHECKED' : '' }}>
                <label>exact match</label>
                <br>
                <br>
                <input class='btn btn-primary' type='submit' value='Search'>
            </form>
        </div>
        <div class="col col-md-8">
            @if(count($stocks) != 0)
                @foreach($stocks as $stock)
                    <h3><a href='{{ $stock->website }}' target='_blank'><img class='stocklogo'
                        @if($stock->logo != null)
                        src= '{{ $stock->logo }}'
                        @else
                        src= '/images/noimage.png'
                        @endif
                        alt='Logo for {{ $stock->ticker }}' title='{{ $stock->website }}'></a>
                        {{ $stock->ticker }} : {{$stock->exchange->exchange_short}}
                        <a class='stockAction' href='/stocks/show/{{ $stock->id }}' title="Analyze stock"><i class='fa fa-line-chart'></i></a>

                        <a class='stockAction' href='/stocks/favorite/{{ $stock->id }}' title="Add to favorites"><i class='fa fa-star'></i></a>

                    </h3>
                @endforeach
            @endif
        </div>
        <div class="col col-md-6">
            {{-- Extracted error code to its own view file --}}
            @include('errors')
        </div>
    </div>

@endsection
