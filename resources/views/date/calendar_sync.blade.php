@extends('layouts.app')

@section('content')

    <ul class="calendar_sync">
    @foreach(power_set($date_types) as $subset)
        <li>{{ implode(' ' . trans('date.and') . ' ', array_map(function($date_type) {return trans('date.' . $date_type);}, $subset)) }}
            <ul>
                <li>Automatische Einrichtung über <a href="webcal://{{ \Config::get('app.domain') }}{{ route('dates.renderIcal', ['show_types' => $subset], false) }}">{{ trans('date.webcal') }}</a>
                <li>Manuelle Einrichtung über HTTP: {{ route('dates.renderIcal', ['show_types' => $subset]) }}</li>
                <!-- TODO: Add GCal synced IDs -->
            </ul>
        </li>
    @endforeach
    </ul>
    {{trans('date.calendar_sync_explanation')}}
@endsection