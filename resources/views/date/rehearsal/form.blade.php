@if(isset($rehearsal))
    <?php Form::setModel($rehearsal); ?>
    <?php $newRehearsal = false; ?>
@else
    <?php $newRehearsal = true; ?>
@endif
<div class="panel-body">
    {!! Form::open($options) !!}
    <div class="row">
        <div class="col-xs-12 col-md-6">
            {!! Form::textInput2d('title') !!}
            {!! Form::textareaInput2d('description') !!}
        </div>
        <div class="col-xs-12 col-md-6">
            {!! Form::datetimeInput2d('start') !!}
            {!! Form::datetimeInput2d('end') !!}
            {!! Form::textInput2d('place') !!}
            {!! Form::checkboxInput2d('has_answer_deadline', $newRehearsal ? false : !is_null($rehearsal->answer_deadline)) !!}
            {!! Form::datetimeInput2d('answer_deadline')!!}
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-6">
            {!! Form::selectInput2d('voice_id', $voices) !!}
            {!! Form::checkboxInput2d('mandatory', $newRehearsal ? true : null) !!}
            {!! Form::checkboxInput2d('binary_answer', $newRehearsal ? true : null) !!}
            {!! Form::numberInput2d('weight', $newRehearsal ? 1.00 : null, ['min' => '0.00', 'max' => '10.00', 'step' => '0.01']) !!}
        </div>
        <div class="col-xs-12 col-md-6">
            {!! Form::submitInput2d() !!}
        </div>
    </div>
    @if ($newRehearsal)
        <div class="row">
            <div class="col-xs-12 col-md-6">
                {!! Form::checkboxInput2d('repeat', false, ['data-toggle' => 'collapse', 'data-target' => '#repeat-rehearsal', 'aria-expanded' => Session::getOldInput('repeat', false) ? 'true' : 'false']) !!}
                <div id="repeat-rehearsal" class="collapse{{ Session::getOldInput('repeat', false) ? ' in' : '' }}">
                    <div class="row">
                        <div class="col-xs-12">
                            {!! Form::dateInput2d('end_repeat') !!}
                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-6 col-lg-4">
                            {!! Form::radioInput2d('interval', trans('form.daily'), 'daily', false) !!}
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-6 col-lg-4">
                            {!! Form::radioInput2d('interval', trans('form.weekly'), 'weekly', true) !!}
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-6 col-lg-4">
                            {!! Form::radioInput2d('interval', trans('form.monthly'), 'monthly', false) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {!! Form::close() !!}
</div>