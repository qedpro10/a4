{{-- /resources/views/stocks/about.blade.php --}}
@extends('layouts.master')

@push('head')
    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/flatly/bootstrap.min.css' rel='stylesheet'>

@endpush

@section('title')
    About DayStocker
@endsection

@section('content')
    <div class="container" id="content">
        <div class="row">
            <div class="col col-md-12">
                <h1>About DayStocker</h1>
            </div>
        </div>
    </div>
    <div class="row" id="content">
        <div class="col col-md-6">
            <blockquote class="pattern" cite='www.themarketguys.com'>
            <p>The <b>Bullish Engulfing Pattern</b> is one of stock trader's favorites because it creates such a strong indication
            of the return of the buyers after a short selloff.  This pattern does not require confirmation: it's okay to enter
            the trade on the day of the bullish engulfing candle without waiting for a follow-up day to show that the uptrend
            is continuing.  The key points of this pattern are the following:</p>
            <ul class="pattern">
                <li class='pattern'>The stock must be in a short-term downtrend within a longer uptrend. This indicates a pullback within a larger positive run. </li>
                <li>The first day of the pattern is a down day, as evidenced by the red (or black) candle. The red candle tells us that the short-term downtrend is still controlled by the sellers who are pushing the stock to lower levels.</li>
               <li>The second day candle should be green (white), indicating that the buyers are in control for that day.</li>
               <li>The key to the Bullish Engulfing Pattern: The second days body must completely engulf the first day's body.  While not required, it shows more strength if the second day's body completely engulfs the first day's body and shadows.</li>
            </ul>
            </blockquote>
        </div>
        <div class="col col-md-6">
            <blockquote class="pattern">
                <img id='bepImage' src='/images/BullishEP.png'/>
                <p>DayStocker is a stock analyzer designed specifically to analyze stocks for the Bullish Pivot Point patterns.</p>
                <p>Users can select stocks for their portfolio and DayStocker will analyze these for the Bullish Engulfing Pattern trend.</p>
                <p>End of day buy, sell, hold recommendations are available.</p>
                <p><a href='/register'>Register</a> to get started now</p>
            </blockquote>
        </div>
    <div>
</div>

@endsection
