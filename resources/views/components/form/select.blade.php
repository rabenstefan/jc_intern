<div class="form-group">
    <?php
    $class = 'form-control form-control-2d';
    if (array_key_exists('class', $attributes)) {
        $class .= ' ' . $attributes['class'];
    }
    ?>
    {{ Form::label($name, trans('form.' . $name)) }}
    {{ Form::select($name, $list, $selected, array_merge($attributes, ['class' => $class])) }}
</div>