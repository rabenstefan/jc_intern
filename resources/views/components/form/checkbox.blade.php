<div class="checkbox">
    <label>
        <?php
            $class = 'form-control form-control-2d';
            if (array_key_exists('class', $attributes)) {
                $class .= ' ' . $attributes['class'];
            }
        ?>
        {{ Form::hidden($name, 0, array_merge($attributes, ['class' => $class])) }}
        {{ Form::checkbox($name, 1, $value, array_merge($attributes, ['class' => $class])) }}
        <span>{{ trans('form.' . $name) }}</span>
    </label>
</div>