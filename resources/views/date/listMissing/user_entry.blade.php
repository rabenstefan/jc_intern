
<div class="col-xs-6 col-sm-3 col-lg-2 names">
    {{ $user->first_name . ' ' . $user->last_name }}
</div>
<div class="col-xs-6 col-sm-3 col-lg-2 sliders">
    <span class="slider-2d" data-function="changeAttendance">
        <input type="checkbox"<?php echo $user->missedRehearsal($currentRehearsal->id) ? '' : ' checked="checked"'; ?> id="slider-attending-{{ $user->id }}">
        <label for="slider-attending-{{ $user->id }}">
            <i class="fa fa-calendar-times-o label-off" title="{{ trans('date.missed') }}"></i>
            <i class="fa fa-calendar-check-o label-on" title="{{ trans('date.attended') }}"></i>
        </label>
    </span>
</div>
