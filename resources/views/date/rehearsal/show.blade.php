@extends('layouts.app')

@section('title'){{ trans('date.rehearsal_show_title') }}@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <h1>{{ trans('date.rehearsal_show_title') }}</h1>

            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-2d">
                        <div class="panel-heading">{{ trans('date.rehearsal_show_title') }}</div>

                        @include('date.rehearsal.form', ['options' => ['url' => route('rehearsal.update', ['rehearsal' => $rehearsal->id]), 'method' => 'PUT'], 'rehearsal' => $rehearsal])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection
