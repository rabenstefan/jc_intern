@extends('layouts.app')

@section('title'){{ trans('date.gig_show_title') }}@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <h1>{{ trans('date.gig_show_title') }}</h1>

            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-2d">
                        <div class="panel-heading">{{ trans('date.gig_show_title') }}</div>

                        <div class="panel-body">
                            {!! Form::model($gig, ['url' => route('gig.update', ['gig' => $gig->id]), 'method' => 'PUT']) !!}
                            <div class="row">
                                <div class="col-xs-12 col-md-6">
                                    {{ Form::textInput2d('title') }}
                                    {{ Form::textareaInput2d('description') }}
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    {{ Form::datetimeInput2d('start') }}
                                    {{ Form::datetimeInput2d('end') }}
                                    {{ Form::textInput2d('place') }}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection
