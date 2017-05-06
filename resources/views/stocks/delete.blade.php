{{-- /resources/views/stocks/delete.blade.php --}}
@extends('layouts.master')

@section('title')
    Confirm deletion: {{ $stock->ticker }}
@endsection

@section('content')

    <h1>Confirm deletion</h1>
    <form method='POST' action='/stocks/delete'>

        {{ csrf_field() }}

        <input type='hidden' name='id' value='{{ $stock->id }}'?>

        <h2>Are you sure you want to delete <em>{{ $stock->ticker }}</em>?</h2>

        <input type='submit' value='Yes, delete this stock.' class='btn btn-danger'>

    </form>

@endsection
