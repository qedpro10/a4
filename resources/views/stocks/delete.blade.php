{{-- /resources/views/stocks/delete.blade.php --}}
@extends('layouts.master')

@section('title')
    Confirm deletion: {{ $stock->ticker }}
@endsection

@section('content')

    <h1>Confirm deletion</h1>
    <form method='POST' id='fcenter' action='/stocks/delete'>

        {{ csrf_field() }}

        <input type='hidden' name='id' value='{{ $stock->id }}'?>

        <h3>Are you sure you want to remove from favorites - <em>{{ $stock->ticker }}</em>?</h3>

        <h2><input type='submit' value='Yes, remove this stock.' class='btn btn-danger'></h2>
    </form>

@endsection
