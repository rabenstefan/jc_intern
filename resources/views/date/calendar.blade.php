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

                            @if (Auth::user()->isAdmin('gig') || Auth::user()->isAdmin('rehearsal'))
                                <div class="pull-right">
                                    {!! Html::addButton(trans('date.add_date'), '#', ['dropdown-toggle'], ['data-toggle' => 'dropdown', 'aria-haspopup' => 'true', 'aria-expanded' => 'false']) !!}

                                    <ul class="dropdown-menu">
                                        @if (Auth::user()->isAdmin('rehearsal'))
                                            <li>
                                                <a href="{{ route('rehearsal.create') }}">{{ trans('nav.rehearsal_create') }}</a>
                                            </li>
                                        @endif
                                        @if (Auth::user()->isAdmin('gig'))
                                            <li>
                                                <a href="{{ route('gig.create') }}">{{ trans('nav.gig_create') }}</a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <div class="panel-body">
                            @include('date.settings_bar', ['view_type' => 'calendar', 'override_types' => $override_types, 'override_statuses' => $override_statuses, 'override_show_all' => $override_show_all])
                            @include('date.calendar.row', ['calendar' => $calendar])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#calendar-dates').find('.fc-button').addClass('btn btn-2d');
        });
    </script>
@endsection
