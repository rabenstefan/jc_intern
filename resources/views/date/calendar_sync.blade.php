@extends('layouts.app')

@section('content')

    <ul class="calendar_sync">
    @foreach(power_set($date_types) as $subset)
        <li>{{ implode(' ' . trans('date.and') . ' ', array_map(function($date_type) {return trans('date.' . $date_type);}, $subset)) }}
            <ul>
                <li>{{ trans('date.subscribe_automatically') }}: <a href="webcal://{{ \Config::get('app.domain') }}{{ route('dates.renderIcal', ['show_types' => $subset], false) }}">{{ trans('date.webcal') }}</a>
                {{-- Automatically adding an iCal to Google Calendar is not officially supported. HTTPS-URLs dont work at all  --}}
                <li>{{ trans('date.subscribe_automatically') }}: <a href="{{'https://calendar.google.com/calendar/r/settings/addbyurl?cpub=false&cid='}}@urlescape('http://' . \Config::get('app.domain') . route('dates.renderIcal', ['show_types' => $subset], false))">{{trans('date.gcal')}} {{ trans('date.takes_30mins') }}</a></li>

            </ul>
        </li>
    @endforeach
    </ul>
    {{trans('date.subscribe_manually')}}:
    <ul>
        @foreach(power_set($date_types) as $subset)
        <li>{{ implode(' ' . trans('date.and') . ' ', array_map(function($date_type) {return trans('date.' . $date_type);}, $subset)) }}: <br> {{ route('dates.renderIcal', ['show_types' => $subset]) }}</li>
        @endforeach
    </ul>
    {{trans('date.calendar_sync_explanation')}}
@endsection