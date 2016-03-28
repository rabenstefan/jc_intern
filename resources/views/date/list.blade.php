@extends('layouts.app')

@section('title'){{ trans('date.index_title') }}@endsection

@section('content')
    <div class="row" id="{{ trans('date.index_title') }}">
        <div class="col-xs-12">
            <h1>{{ trans('date.index_title') }}</h1>

            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-2d">
                        <div class="panel-heading">{{ trans('date.index_title') }}</div>

                        <div class="panel-body" id="list-dates">
                            @include('date.set_chooser', ['view_type' => 'list'])
                            @each('date.list.row', $dates, 'date')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection
