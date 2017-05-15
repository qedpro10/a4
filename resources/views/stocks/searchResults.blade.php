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
                vAxis: {title: 'Stock Price ($)'},
                hAxis: {title: 'per day'}
            };
            var chart = new google.visualization.LineChart(document.getElementById('linechart'));
            chart.draw(data, options);
        }
    </script>
@endpush

@section('content')
    <div class="container">
        <div class="row">
            <div class="col col-md-12">
                <h1>Search Results</h1>
            </div>
        </div>
        <div class="row">
            <div class="col col-md-6">
                <h2>{{ $searchTicker }}: {{ $current['StockExchange'] }}</h2>
                <h3>{{ $current['Name'] }}</h3>
                <h5>Open: ${{ $current['Open'] }}</h5>
                <h5>Day High: ${{ $current['DaysHigh'] }}</h5>
                <h5>Day Low: ${{ $current['DaysLow'] }}</h5>
                <h5>Volume: {{ $current['Volume'] }} shares</h5>
                <h5>Dividend Date: {{ $current['ExDividendDate'] == '' ? "none" : $current['ExDividendDate'] }} </h5>
            </div>
            <div class="col col-md-6">
                <h4>30-day Closing</h4>
                <div id='linechart' style="width: 500px; "></div>
            </div>
        </div>
        <div class="row">
            <div class="col col-md-12">
                <form method='POST' class='fcenter' action='/stocks/new'>
                    {{ csrf_field() }}

                    <input type='hidden' name='ticker' id='ticker' value='{{ $searchTicker }}'>
                    <input type='hidden' name='exchange' id='exchange' value='{{ $current['StockExchange'] }}'>
                    <input type='hidden' name='company_name' id='company_name' value='{{ $current['Name'] }}'>
                    <input type='hidden' name='dividend' id='dividend' value='{{ $current['ExDividendDate'] }}'>

                    <input class='btn btn-primary' type='submit' value='Add To Portfolio'>
                </form>
            </div>
        </div>
    </div>

@endsection
