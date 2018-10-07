@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-2d">
                <div class="panel-heading">{{ trans('warning.header') }}</div>
                <div class="panel-body">
                    {!! trans('warning.body') !!}
                    <form class="form-horizontal" role="form" method="POST" action="{{ url()->current() }}">
                        {!! csrf_field() !!}

                        {!! Form::hidden('accepted_warning', true) !!}

                        {!! Form::submitInput2d('Na Gut') !!}
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
