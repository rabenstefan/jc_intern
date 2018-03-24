@extends('layouts.app')

@section('content')
    {{trans('date.calendar_sync_explanation')}}
    <ul class="calendar_sync">
    @foreach(power_set($date_types) as $subset)
        <li>
            {{ implode(' ' . trans('date.and') . ' ', array_map(function($date_type) {return trans('date.' . $date_type);}, $subset)) }}
            <ul>
                <li><a href="{{ route('date.renderIcal', ['show_types' => $subset]) }}">{{ trans('date.http') }}</a></li>
                <li><a href="webcal://{{ \Config::get('app.domain') }}{{ route('date.renderIcal', ['show_types' => $subset], false) }}">{{ trans('date.webcal') }}</a></li>
            </ul>
        </li>
    @endforeach
    </ul>
@endsection