@extends('app')

@section('content')
    <div class="container">
        <h1 class="mt-5">{{ $code }}</h1>
        <p class="lead">{{ $message }}</p>
    </div>
@stop
