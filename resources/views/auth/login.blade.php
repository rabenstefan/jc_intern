@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-2d">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    <noscript>
                        <div><p>{{ trans('home.noscript_body') }}</p>
                            <a href="https://www.enable-javascript.com" target="_blank" rel="noreferrer noopener">{{ trans('home.noscript_link') }}</a>
                        </div>
                    </noscript>
                    <form class="form-horizontal hide-from-noscript" role="form" method="POST" action="{{ url('/login') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">{{ trans('form.email') }}</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control form-control-2d" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">{{ trans('form.old_password') }}</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control form-control-2d" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember">
                                        <span>{{ trans('auth.remember_me') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-2d">
                                    <i class="fas fa-btn fa-sign-in-alt"></i>&nbsp;Login
                                </button>
                                <br>
                                <a class="btn btn-link" href="{{ url('/password/reset') }}">{{ trans('auth.forgot_password') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
