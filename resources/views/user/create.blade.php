@extends('layouts.app')

@section('title'){{ trans('user.create_title') }}@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1>{{ trans('user.create_title') }}</h1>
            @include('user.form', ['options' => ['route' => 'user.store', 'method' => 'POST']])
        </div>
    </div>
@endsection