<div class="form-group">
    {{ Form::label($name, trans('form.' . $name)) }}
    {{ Form::text($name, $value, array_merge(['class' => 'form-control form-control-2d'], $attributes)) }}
</div>