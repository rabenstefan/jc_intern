@extends('layouts.app')

@section('content')

    @foreach(power_set($date_types) as $subset)
        <a href="{{ route('date.renderIcal', ['show_types' => $subset]) }}">{{ json_encode($subset) }}</a>
    @endforeach
@endsection