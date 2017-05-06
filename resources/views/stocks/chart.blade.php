{{-- /resources/views/stocks/chart.blade.php --}}
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
              legend:'none'
            };
            var chart = new google.visualization.CandlestickChart(document.getElementById('candlechart'));
            chart.draw(data, options);
        }
    </script>


@endpush

@section('title')

@endsection

@section('content')

    <div id="candlechart" style="width: 900px; height: 500px"></div>

@endsection
