<?php $comment = $currentRehearsal->hasCommented($user) ? $currentRehearsal->getComment($user) : ''; ?>
<?php $mark = $currentRehearsal->hasCommented($user) ? '<i class="far fa-comment"></i>' : ''; ?>
<div class="col-xs-6 col-sm-3 col-lg-2 names" title="{{ $comment }}">
    {{ $user->first_name . ' ' . $user->last_name }}
    <span class="pull-right">
        {!! $mark !!}
    </span>
</div>

<div class="col-xs-6 col-sm-3 col-lg-2 sliders">
    <span class="slider-2d" data-function="excuseMissing" data-change-url="{{ route('attendances.changeOwnAttendance', ['events_name' => 'rehearsals','event_id' => $currentRehearsal->id, 'user_id' => $user->id, 'shorthand' => 'excuse']) }}">
        <input type="checkbox" {!! $user->excusedRehearsal($currentRehearsal->id) ? 'checked="checked"' : '' !!} id="slider-attending-{{ $user->id }}">
        <label for="slider-attending-{{ $user->id }}">
            <span class="slider slider-attendance"></span>
            <i class="fa fa-times" title="{{ trans('date.not_excused') }}"></i>
            <i class="fa fa-check" title="{{ trans('date.excused') }}"></i>
        </label>
    </span>
</div>
<div class="clearfix visible-xs-block"></div>