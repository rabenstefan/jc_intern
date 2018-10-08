<div class="form-group">
    {{ Form::label($name, trans('form.' . $name)) }}
    @if(false !== $helpBubble)
        {{ Form::helpBubble($helpBubble) }}
    @endif
    <?php
    $class = 'form-control form-control-2d';
    if (array_key_exists('class', $attributes)) {
        $class .= ' ' . $attributes['class'];
    }
    ?>
    {{ Form::date($name, null, array_merge($attributes, ['class' => $class])) }}
</div>