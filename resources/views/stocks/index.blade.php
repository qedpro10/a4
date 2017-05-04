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
            <h2>{{ $stock->ticker }}</h2>
            <img src='{{ $stock->logo }}'>
        @endforeach
    </div>
@endsection
