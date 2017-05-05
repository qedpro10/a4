{{-- /resources/views/stocker/index.blade.php --}}
@extends ('layouts.master')

@section('title')
    DayStocker
@endsection

@section('picture')

@endsection

@section('content')
    <div class='stock'>
        @foreach($stocks as $stock)
            <h2>{{ $stock->ticker }} : {{$stock->exchange->exchange_short}}</h2>


        @endforeach
    </div>
@endsection
