<div class="form-group">
    {{ Form::label($name, trans('form.' . $name)) }}
    {{ Form::date($name, null, array_merge(['class' => 'form-control form-control-2d'], $attributes)) }}
</div>