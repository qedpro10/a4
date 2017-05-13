{{-- /resources/views/stocks/show.blade.php --}}
@extends('layouts.master')

@push('head')
    <link href='/css/stocks.css' rel='stylesheet'>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart () {
            var data = new google.visualization.arrayToDataTable(
                <?php echo $histData ?>
            , true);

            var options = {
                legend: 'none',
                title: 'Bullish Engulfing Pattern Analysis',
                vAxis: {title: 'Stock Price ($)'},
                hAxis: {title: 'per day'}
            };
            var chart = new google.visualization.CandlestickChart(document.getElementById('candlechart'));
            chart.draw(data, options);
        }
    </script>
@endpush

@section('title')
    {{ $stock->ticker }}
@endsection

@section('content')
    <h1>
        <a href='{{ $stock->website }}' target='_blank'><img class='stocklogo'
            src='{{ $stock->logo }}' alt='Logo for {{ $stock->ticker }}' title='{{ $stock->website }}'></a>
        {{ $stock->ticker }}

        <a class='stockAction' href='/stocks/{{ $stock->id }}/delete'><i class='fa fa-star' title="Remove from favorites"></i></a>
        <a class='stockAction' href='/stocks/edit/{{ $stock->id }}'><i class='fa fa-pencil' title="Edit stock info"></i></a>
    </h1>
    <div class='content cf'>
        <h3>{{ $stock->company_name }} </h3>
        <p>Watching since: {{ $stock->created_at }}</p>
        <p>Last updated: {{ $stock->updated_at }}</p>
        <br>
        <p>Days Range: ${{ $current['DaysRange'] }}</p>
        <p>50-day Moving Average: ${{ $current['FiftydayMovingAverage'] }}
        <p>Change from 50-day Moving Average: {{ $current['PercentChangeFromFiftydayMovingAverage'] }}
        <p>200-day Moving Average: ${{ $current['TwoHundreddayMovingAverage'] }}
        <p>Change from 200-day Moving Average: {{ $current['PercentChangeFromTwoHundreddayMovingAverage'] }}
        <p>Volume: {{ $current['Volume'] }} shares</p>
    </div>
    <div class= 'chart' id="candlechart" style="width: 500px; height: 300px"></div>
@endsection
