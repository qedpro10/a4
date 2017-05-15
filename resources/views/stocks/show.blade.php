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
    <div class="container">
        <div class="row">
            <div class="col col-md-12 content cf">
                <h1>
                    <a href='{{ $stock->website }}' target='_blank'><img class='stocklogo'
                        @if($stock->logo != null)
                        src= '{{ $stock->logo }}'
                        @else
                        src= '/images/noimage.png'
                        @endif
                        alt='Logo {{ $stock->ticker }}' title='{{ $stock->website }}'></a>

                    {{ $stock->ticker }}

                    <a class='stockAction' href='/stocks/delete/{{ $stock->id }}'><i class='fa fa-star' title="Remove from favorites"></i></a>

                </h1>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col col-md-6">
                <h4>{{ $stock->company_name }} </h4>
                <p>Watching since: {{ $stock->created_at }}</p>
                <p>Last updated: {{ $stock->updated_at }}</p>
                <br>
                @if ($current != null)
                    <p>Days Range: ${{ $current['DaysRange'] }}</p>
                    <p>50-day Moving Average: ${{ $current['FiftydayMovingAverage'] }}
                    <p>Change from 50-day Moving Average: {{ $current['PercentChangeFromFiftydayMovingAverage'] }}
                    <p>200-day Moving Average: ${{ $current['TwoHundreddayMovingAverage'] }}
                    <p>Change from 200-day Moving Average: {{ $current['PercentChangeFromTwoHundreddayMovingAverage'] }}
                    <p>Volume: {{ $current['Volume'] }} shares</p>
                @endif
                <p>Dividend: {{ $stock->dividend}} </p>
            </div>
            <div class="col col-md-6">
                <h4>Bullish Engulfing Pattern Recommendation: {{ $recommendation }}</h4>
                <div class='chart' id='candlechart'>
            </div>
        </div>
    </div>

@endsection
