{{-- /resources/views/stocks/show.blade.php --}}
@extends('layouts.master')

@push('head')
    <link href='/css/stocks.css' rel='stylesheet'>
@endpush

@section('title')
    {{ $stock->ticker }}
@endsection

@section('content')

    <div class='stock cf'>

        <h1>{{ $stock->ticker }}</h1>

        <a href='{{ $stock->website }}' target='_blank'><img class='stocklogo' src='{{ $stock->logo }}' alt='Logo for {{ $stock->ticker }}'></a>

        <p>Watching since: {{ $stock->created_at }}</p>
        <p>Last updated: {{ $stock->updated_at }}</p>
        <br>
        <p>Open: {{ $current['Open'] }}</p>
        <p>Day High: {{ $current['DaysHigh'] }}</p>
        <p>Day Low: {{ $current['DaysLow'] }}</p>
        <p>Volume: {{ $current['Volume'] }}</p>

        {!!ChartManager::setChartType('bar-chart')
                        ->setOptions($options)
                        ->setCols($cols)
                        ->setRows($rows)
                     ->render()!!}


        <a class='stockAction' href='/stocks/edit/{{ $stock->id }}'><i class='fa fa-pencil'></i></a>
        <a class='stockAction' href='/stocks/{{ $stock->id }}/delete'><i class='fa fa-trash'></i></a>

    </div>
@endsection
