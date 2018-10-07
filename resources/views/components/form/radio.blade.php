<div class="radio">
    <label>
        <?php
        $class = 'form-control form-control-2d';
        if (array_key_exists('class', $attributes)) {
            $class .= ' ' . $attributes['class'];
        }
        ?>
        {{ Form::radio($name, $value, $checked, array_merge($attributes, ['class' => $class])) }}
        <span>{{ $label }}</span>
    </label>
</div>