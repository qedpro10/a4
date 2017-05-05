{{-- /resources/views/stocker/index.blade.php --}}
@extends ('layouts.master')

@section('title')
    DayStocker
@endsection

@section('picture')

@endsection

@section('content')
    <section id='stocks' class='cf'>
        <h2>Your Stocks</h2>
        @if(count($stocks) == 0)
            You not watching any stocks yet; would you like to <a href='/stocks/new'>add one</a>?
        @else
            @foreach($stocks as $stock)

                <div class='stock cf'>
                    <h2>{{ $stock->ticker }} : {{$stock->exchange->exchange_short}}</h2>
                    <span>
                    <a class='stockAction' href='/stocks/{{ $stock->id }}'><i class='fa fa-line-chart'></i></a>
                    <a class='stockAction' href='/stocks/edit/{{ $stock->id }}'><i class='fa fa-pencil'></i></a>
                    <a class='stockAction' href='/stocks/delete/{{ $stock->id }}'><i class='fa fa-trash'></i></a></span>

                </div>
            @endforeach
        @endif
    </section>
    <div class='stock'>
        @foreach($stocks as $stock)



        @endforeach
    </div>
@endsection
