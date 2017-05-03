{{-- /resources/views/stocker/main.blade.php --}}
@extends ('layouts.master')

@section('title')
    Stocker
@endsection

@section('picture')

@endsection

@section('content')
    <h4>Stocker Main Page</h4>
    <br>
    <form method='POST' action='/main'>
        {{ csrf_field() }}
    </form>

@endsection
