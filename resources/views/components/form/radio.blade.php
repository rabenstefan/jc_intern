<div class="radio">
    <label>
        {{ Form::radio($name, $value, $checked, array_merge(['class' => 'form-control form-control-2d'], $attributes)) }}
        <span>{{ $label }}</span>
    </label>
</div>