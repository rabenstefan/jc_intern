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
                        <div class="panel-heading">
                            {{ trans('date.index_title') }}

                            <div class="pull-right">
                                <a href="#" title="{{ trans('date.add_date') }}" class="btn btn-2d btn-add dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-plus"></i>&nbsp;{{ trans('date.add_date') }}
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('user.index') }}">{{ trans('nav.user_list') }}</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('user.show', Auth::user()->id) }}">{{ trans('nav.user_show_own') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="panel-body">
                            @include('date.settings_bar', ['view_type' => 'calendar'])
                            @include('date.calendar.row', ['calendar' => $calendar])
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
