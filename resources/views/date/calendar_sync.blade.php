@extends('layouts.app')

@section('content')

    <ul class="calendar_sync">
    @foreach(power_set($date_types) as $subset)
        <li>{{ implode(' ' . trans('date.and') . ' ', array_map(function($date_type) {return trans('date.' . $date_type);}, $subset)) }}
            <ul>
                <li>Automatische Einrichtung über <a href="webcal://{{ \Config::get('app.domain') }}{{ route('dates.renderIcal', ['show_types' => $subset], false) }}">{{ trans('date.webcal') }}</a>
                {{-- Automatically adding an iCal to Google Calendar is not officially supported. HTTPS-URLs dont work at all  --}}
                <li>Automatische Einrichtung für <a href="{{'https://calendar.google.com/calendar/r/settings/addbyurl?cpub=false&cid='}}@urlescape('http://' . \Config::get('app.domain') . route('dates.renderIcal', ['show_types' => $subset], false))">Google Calendar</a> (dauert bis zu 30min)</li>
                <li>Link für manuelle Einrichtung: {{ route('dates.renderIcal', ['show_types' => $subset]) }}</li>
                @urlescape('https://testing15522fsdgse.jazzchor-bonn.de/render_ical?show_types%5B0%5D=gigs')
            </ul>
        </li>
    @endforeach
    </ul>
    {{trans('date.calendar_sync_explanation')}}
@endsection