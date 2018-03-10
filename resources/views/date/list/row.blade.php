@if($date->getShortName() == 'rehearsal' && !$date->isAttending(Auth::user()))
    <?php $notAttending = true; ?>
@else
    <?php $notAttending = false; ?>
@endif

<div class="row list-item">
    <div class="col-xs-12 context-box-2d event event-{{ $date->getShortName() }}{{ $notAttending ? ' event-not-going' : '' }}">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-8 col-lg-10">
                <h4 class="title">
                    {{ $date->getTitle() }}
                    <span class="not-going-note" style="display: <?php echo $notAttending ? 'inline' : 'none'; ?>;">{{  ' &ndash; ' . trans('date.not_attending') }}</span>
                    @if($date->hasPlace())
                        <br>
                        {{ $date->place }}
                        <a href="https://www.google.com/maps/search/{{ $date->place }}/" title="{{ trans('date.address_search') }}" target="_blank" class="pull-right text-large">{{ trans('date.goto_maps') }} <i class="fa fa-map-o"></i></a>
                    @endif
                </h4>

                <p class="date">
                    @if($date->isAllDay())
                        {{ $date->getStart()->formatLocalized('%A, den %d.%m.%Y') }}
                    @else
                        {{ $date->getStart()->formatLocalized('%A, den %d.%m.%Y') }}
                        <br>
                        {{ $date->getStart()->formatLocalized('%H:%M') . ' - ' . $date->getEnd()->formatLocalized('%H:%M') }}
                    @endif
                </p>
                <span class="date_descr">
                    {{ $date->description }}
                </span>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2 event-controls">
                @if(isset($date->getEventOptions()['url']) && Auth::user()->isAdmin($date->getShortName()))
                    <span>
                        <a href="{{ $date->getEventOptions()['url'] }}" class="btn btn-2d" title="{{ trans('form.edit') }}">
                            <i class="fa fa-pencil"></i>
                        </a>
                    </span>
                @endif
                @if($date->getShortName() == 'rehearsal')
                    <span class="slider-2d" data-function="changeAttendance" data-excuse-url="{{ route('attendance.excuseSelf', ['rehearsal_id' => $date->getId()]) }}" data-attend-url="{{ route('attendance.confirmSelf', ['rehearsal_id' => $date->getId()]) }}">
                        <input type="checkbox"<?php echo $notAttending ? '' : ' checked="checked"'; ?> id="slider-attending-{{ $date->getShortName() }}-{{ $date->getId() }}">
                        <label for="slider-attending-{{ $date->getShortName() }}-{{ $date->getId() }}">
                            <i class="fa fa-calendar-times-o label-off" title="{{ trans('date.excuse') }}"></i>
                            <i class="fa fa-calendar-check-o label-on" title="{{ trans('date.attend') }}"></i>
                        </label>
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>