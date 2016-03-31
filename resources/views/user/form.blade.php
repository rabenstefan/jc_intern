@if(isset($user))
    <?php Form::setModel($user); ?>
@endif
{!! Form::open($options) !!}
<div class="panel panel-2d">
    <div class="panel-heading">
        {{ trans('user.data') }}
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-12 col-md-6">
                {!! Form::textInput2d('first_name') !!}
                {!! Form::textInput2d('last_name') !!}
                {!! Form::textInput2d('email') !!}
                {!! Form::dateInput2d('birthday') !!}
            </div>
            <div class="col-xs-12 col-md-6">
                {!! Form::textInput2d('phone') !!}
                {!! Form::textInput2d('address_street') !!}
                {!! Form::textInput2d('address_zip') !!}
                {!! Form::textInput2d('address_city') !!}
            </div>
            <div class="hidden-xs hidden-sm col-xs-12 col-md-6">
                {!! Form::passwordInput2d('password') !!}
                @if(empty($user))
                    <p>{{ trans('user.password_note') }}</p>
                @endif
                <span class="center-block">
                    {!! Form::submit(trans('user.save'), ['class' => 'btn btn-lg btn-2d']) !!}
                </span>
            </div>
            <div class="col-xs-12 col-md-6">
                {!! Form::selectInput2d('voice_id', App\Voice::getChildVoices()->pluck('name', 'id')->toArray(), $voice) !!}
                {!! Form::checkboxInput2d('sheets_deposit_returned') !!}
                @if(isset($user))
                <p>{!! trans('user.last_echo', ['semester' => App\Semester::find($user->last_echo)->label]) !!}</p>
                @endif
            </div>
            <div class="hidden-lg hidden-md col-xs-12 col-md-6">
                {!! Form::passwordInput2d('password') !!}
                @if(empty($user))
                    <p>{{ trans('user.password_note') }}</p>
                @endif
                <span class="center-block">
                    {!! Form::submitInput2d() !!}
                </span>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}