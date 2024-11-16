@extends('layouts.app')

@section('content')
    <iframe
        src="{{ $url }}"
        style="display: block; height: 100vh; width: 100vw; border: none;"
    ></iframe>
@endsection
