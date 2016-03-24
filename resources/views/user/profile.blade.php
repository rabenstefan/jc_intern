@extends('layouts.app')

@section('title'){{ trans('user.profile_title_short', ['fname' => $user->first_name, 'lname' => $user->last_name]) }}@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1>{{ trans('user.profile_title', ['fname' => $user->first_name, 'lname' => $user->last_name]) }}</h1>
            {{ Form::model($user, ['route' => ['user.update', $user->id], 'method' => 'PUT']) }}
                <div class="panel panel-2d">
                    <div class="panel-heading">
                        {{ trans('user.data') }}
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group">
                                    {{ Form::label('first_name', trans('user.first_name')) }}
                                    {{ Form::text('first_name', null, ['class' => 'form-control  form-control-2d']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('last_name', trans('user.last_name')) }}
                                    {{ Form::text('last_name', null, ['class' => 'form-control  form-control-2d']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('email', trans('user.email')) }}
                                    {{ Form::text('email', null, ['class' => 'form-control  form-control-2d']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('birthday', trans('user.birthday')) }}
                                    {{ Form::date('birthday', null, ['class' => 'form-control  form-control-2d']) }}
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group">
                                    {{ Form::label('phone', trans('user.phone')) }}
                                    {{ Form::text('phone', null, ['class' => 'form-control  form-control-2d']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('address_street', trans('user.address_street')) }}
                                    {{ Form::text('address_street', null, ['class' => 'form-control  form-control-2d']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('address_zip', trans('user.address_zip')) }}
                                    {{ Form::text('address_zip', null, ['class' => 'form-control  form-control-2d']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('address_city', trans('user.address_city')) }}
                                    {{ Form::text('address_city', null, ['class' => 'form-control  form-control-2d']) }}
                                </div>
                            </div>
                            <div class="hidden-xs hidden-sm col-xs-12 col-md-6" style="margin-top: 1em">
                                {{ Form::submit(trans('user.save'), ['class' => 'btn btn-lg btn-2d center-block']) }}
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group">
                                    {{ Form::label('voice_id', trans('user.voice_id')) }}
                                    {{ Form::select('voice_id', App\Voice::getChildVoices()->pluck('name', 'id')->toArray(), null, ['class' => 'form-control form-control-2d']) }}
                                </div>
                                <div class="checkbox">
                                    <label>
                                        {{ Form::checkbox('sheets_deposit_returned', 1, null, ['class' => '']) }}
                                        <span>{{ trans('user.sheets_deposit_returned') }}</span>
                                    </label>
                                </div>
                                <p>{!! trans('user.last_echo', ['semester' => App\Semester::find($user->last_echo)->label]) !!}</p>
                            </div>
                            <div class="hidden-lg hidden-md col-xs-12 col-md-6">
                                {{ Form::submit(trans('user.save'), ['class' => 'btn btn-lg btn-2d center-block']) }}
                            </div>
                        </div>
                    </div>
                </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection