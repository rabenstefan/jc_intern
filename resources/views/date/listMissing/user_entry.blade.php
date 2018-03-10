
<div class="col-xs-6 col-sm-3 col-lg-2 names">
    {{ $user->first_name . ' ' . $user->last_name }}
    @if($user->excusedRehearsal($currentRehearsal->id))
        <span class="pull-right"><i class="fa fa-bed" title="{{ trans('date.excused') }}"></i></span>
    @endif
</div>
<div class="col-xs-6 col-sm-3 col-lg-2 sliders">
    <span class="slider-2d" data-function="changeAttendance" data-attendance-url="{{ route('attendance.changeAttendance', ['rehearsal_id' => $currentRehearsal->id, 'user_id' => $user->id]) }}">
        <input type="checkbox"<?php echo $user->missedRehearsal($currentRehearsal->id) ? '' : ' checked="checked"'; ?> id="slider-attending-{{ $user->id }}">
        <label for="slider-attending-{{ $user->id }}">
            <i class="fa fa-calendar-times-o label-off" title="{{ trans('date.missed') }}"></i>
            <i class="fa fa-calendar-check-o label-on" title="{{ trans('date.attended') }}"></i>
        </label>
    </span>
</div>
