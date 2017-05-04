@extends('layouts.master')

@push('head')
    <link href='/css/stocks.css' rel='stylesheet'>
@endpush

@section('title')
    {{ $stock->ticker }}
@endsection

@section('content')

    <div class='stock cf'>

        <h1>{{ $stock->ticker }}</h1>

        <a href='/stocks/{{ $stock->id }}'><img class='logo' src='{{ $stock->logo }}' alt='Logo for {{ $stock->ticker }}'></a>

        <p>Company Website: {{ $stock->website }}</p>

        <p>Added on: {{ $stock->created_at }}</p>

        <p>Last updated: {{ $stock->updated_at }}</p>

        <a class='stockAction' href='/stocks/edit/{{ $stock->id }}'><i class='fa fa-pencil'></i></a>
        <a class='stockAction' href='/stocks/{{ $stock->id }}/delete'><i class='fa fa-trash'></i></a>

    </div>
@endsection
