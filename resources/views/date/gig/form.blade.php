@if(isset($gig))
    <?php Form::setModel($gig); ?>
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
            {!! Form::submitInput2d() !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>