@extends('layouts.app')

@section('title'){{ trans('user.profile_title_short', ['fname' => $user->first_name, 'lname' => $user->last_name]) }}@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1>{{ trans('user.profile_title', ['fname' => $user->first_name, 'lname' => $user->last_name]) }}</h1>
            @include('user.form', ['options' => ['route' => ['user.update', $user->id], 'method' => 'PUT']])
        </div>
    </div>
@endsection