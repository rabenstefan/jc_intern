@if(isset($rehearsal))
    <?php Form::setModel($rehearsal); ?>
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
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-6">
            {!! Form::selectInput2d('voice_id', $voices) !!}
            {!! Form::checkboxInput2d('mandatory') !!}
            {!! Form::numberInput2d('weight', 1.00, ['min' => '0.00', 'max' => '1.00', 'step' => '0.01']) !!}
        </div>
        <div class="col-xs-12 col-md-6">
            {!! Form::submitInput2d() !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>