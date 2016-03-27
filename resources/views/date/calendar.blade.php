@extends('layouts.app')

@section('title'){{ trans('date.index_title') }}@endsection

@section('additional_css_files')
    {!! Html::style('css/fullcalendar.min.css') !!}
@endsection

@section('additional_js_files')
    {!! Html::script('js/jquery-ui.custom.min.js') !!}
    {!! Html::script('js/moment.min.js') !!}
    {!! Html::script('js/fullcalendar.min.js') !!}
    {!! Html::script('js/lang/de.js') !!}
@endsection

@section('content')
    <div class="row" id="{{ trans('date.index_title') }}">
        <div class="col-xs-12">
            <h1>{{ trans('date.index_title') }}</h1>

            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-2d">
                        <div class="panel-heading">{{ trans('date.index_title') }}</div>

                        <div class="panel-body">
                            {!! $calendar->calendar() !!}
                            {!! $calendar->script() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    $(document).ready(function () {
        $('#calendar-dates').find('.fc-button').addClass('btn btn-2d');
    });
@endsection
