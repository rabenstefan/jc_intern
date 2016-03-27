<div class="checkbox">
    <label>
        {{ Form::hidden($name, 0, array_merge(['class' => 'form-control form-control-2d'], $attributes)) }}
        {{ Form::checkbox($name, 1, $value, array_merge(['class' => 'form-control form-control-2d'], $attributes)) }}
        <span>{{ trans('form.' . $name) }}</span>
    </label>
</div>