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
    <h1>{{ $stock->ticker }}</h1>
    <div class='content cf'>

        <a href='{{ $stock->website }}' target='_blank'><img class='stocklogo' src='{{ $stock->logo }}' alt='Logo for {{ $stock->ticker }}'></a>

        <p>Watching since: {{ $stock->created_at }}</p>
        <p>Last updated: {{ $stock->updated_at }}</p>
        <br>
        <p>Open: ${{ $current['Open'] }}</p>
        <p>Day High: ${{ $current['DaysHigh'] }}</p>
        <p>Day Low: ${{ $current['DaysLow'] }}</p>
        <p>Volume: {{ $current['Volume'] }} shares</p>

        <a class='stockAction' href='/stocks/edit/{{ $stock->id }}'><i class='fa fa-pencil'></i></a>
        <a class='stockAction' href='/stocks/{{ $stock->id }}/delete'><i class='fa fa-trash'></i></a>

    </div>
    <div class= 'chart' id="candlechart" style="width: 500px; height: 300px"></div>
@endsection
