{{-- /resources/views/stocker/index.blade.php --}}
@extends ('layouts.master')

@section('title')
    DayStocker
@endsection

@section('picture')

@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col col-md-12">
                <h2>Your Stocks</h2>

            </div>
        </div>
        <div class="row">
            <div class="col col-md-12">
                @if(count($stocks) == 0)
                    You are not watching any stocks yet; would you like to <a href='/stocks/search'>add one?</a>?
                @else
                    @foreach($stocks as $stock)
                        <h3><a href='{{ $stock->website }}' target='_blank'><img class='stocklogo'
                            @if($stock->logo != null)
                            src= '{{ $stock->logo }}'
                            @else
                            src= '/images/noimage.png'
                            @endif
                            alt='Logo for {{ $stock->ticker }}' title='{{ $stock->website }}'></a>
                            {{ $stock->ticker }} : {{$stock->exchange->exchange_short}}
                            <a class='stockAction' href='/stocks/show/{{ $stock->id }}' title="Analyze stock"><i class='fa fa-line-chart'></i></a>

                            <a class='stockAction' href='/stocks/delete/{{ $stock->id }}' title="Remove from favorites"><i class='fa fa-star'></i></a>

                            <a class='stockAction' href='/stocks/edit/{{ $stock->id }}' title="Edit Stock"><i class='fa fa-pencil'></i></a>
                        </h3>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

@endsection
