@extends('layouts.app')

@section('title'){{ trans('date.gig_create_title') }}@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <h1>{{ trans('date.gig_create_title') }}</h1>

            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-2d">
                        <div class="panel-heading">{{ trans('date.gig_create_title') }}</div>

                        @include('date.gig.form', ['options' => ['url' => route('gig.store'), 'method' => 'POST']])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection
