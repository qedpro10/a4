{{-- /resources/views/stocks/searchResults.blade.php --}}
@extends('layouts.master')


@section('title')
    New stock search
@endsection

@push('head')
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart', 'line']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart () {
            var data = new google.visualization.arrayToDataTable(
                <?php echo $histData ?>
            , true);

            var options = {
                legend: 'none',
                title: '30 day Closing',
                vAxis: {title: 'Stock Price ($)'},
                hAxis: {title: 'per day'}
            };
            var chart = new google.visualization.LineChart(document.getElementById('linechart'));
            chart.draw(data, options);
        }
    </script>
@endpush


@section('content')
    <div>
        <h1>Search Results</h1>
        <h2>{{ $searchTicker }}: {{ $current['StockExchange'] }}</h2>
        <h3>{{ $current['Name'] }}</h3>
        <h5>Open: ${{ $current['Open'] }}</h5>
        <h5>Day High: ${{ $current['DaysHigh'] }}</h5>
        <h5>Day Low: ${{ $current['DaysLow'] }}</h5>
        <h5>Volume: {{ $current['Volume'] }} shares</h5>
    </div>
    <div id='linechart' style="width: 500px; "></div>
    <div>
        <form method='POST' action='/stocks/new'>
            {{ csrf_field() }}

            <input type='hidden' name='ticker' id='ticker' value='{{ $searchTicker }}'?>
            <input type='hidden' name='exchange' id='exchange' value='{{ $current['StockExchange'] }}'?>
            <input type='hidden' name='company_name' id='company_name' value='{{ $current['Name'] }}'?>
            {{-- Extracted error code to its own view file --}}
            @include('errors')

            <input class='btn btn-primary' type='submit' value='Add To Portfolio'>
        </form>
    </div>






@endsection
