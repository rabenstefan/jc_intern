@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1>{{ trans('home.dashboard') }}</h1>

            <div class="panel panel-2d">
                <div class="panel-heading">{{ trans('home.welcome_title', ['name' => Auth::user()->first_name ]) }}</div>

                <div class="panel-body">

                </div>
            </div>
        </div>
    </div>
@endsection
