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
            You not watching any stocks yet; would you like to <a href='/stocks/search'>add one</a>?
        @else
            @foreach($stocks as $stock)

                <div class='stock cf'>
                    <h2>{{ $stock->ticker }} : {{$stock->exchange->exchange_short}}</h2>
                    <acronym title="Analyze stock">
                        <a class='stockAction' href='/stocks/show/{{ $stock->id }}'><i class='fa fa-line-chart'></i></a>
                    </acronym>
                    <acronym title="Remove from favorites">
                        <a class='stockAction' href='/stocks/delete/{{ $stock->id }}'><i class='fa fa-star'></i></a>
                    </acronym>
                    <acronym title="Edit Stock">
                        <a class='stockAction' href='/stocks/edit/{{ $stock->id }}'><i class='fa fa-pencil'></i></a>
                    </acronym>
                </div>
            @endforeach
        @endif
    </section>
    <div class='stock'>
        @foreach($stocks as $stock)



        @endforeach
    </div>
@endsection
