<div class="row list-item" data-filters='["{{ implode('", "', $date->getApplicableFilters()) }}"]'>
    <div class="col-xs-12 context-box-2d event event-{{ implode(" event-", $date->getApplicableFilters()) }}">
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-md-8 col-lg-10">
                <h4 class="title">
                    {{ $date->getTitle() }}
                    <span class="not-going-note" style="{{ in_array('not-going', $date->getApplicableFilters())  ? 'display: inline;' : 'display: none;' }};">
                        {{ ' &ndash; ' . trans('date.not_attending') }}
                    </span>
                    @if($date->hasPlace())
                        <br>
                        {{ $date->place }}
                        <a href="https://www.google.com/maps/search/@urlescape($date->place)/" title="{{ trans('date.address_search') }}" target="_blank" class="pull-right text-large">
                            {{ trans('date.goto_maps') }} <i class="far fa-map"></i>
                        </a>
                    @endif
                </h4>

                <p class="date">
                    @if($date->isAllDay())
                        {{ $date->getStart()->formatLocalized('%A, den %d.%m.%Y') }}
                    @else
                        @if(0 === $date->getEnd()->diffInDays($date->getStart()))
                            {{ $date->getStart()->formatLocalized('%A, den %d.%m.%Y') }}
                            <br>
                            {{ $date->getStart()->formatLocalized('%H:%M') . ' - ' . $date->getEnd()->formatLocalized('%H:%M') }}
                        @else
                            {{ $date->getStart()->formatLocalized('%A, den %d.%m.%Y %H:%M') }}
                            <br>
                            bis&nbsp;{{ $date->getEnd()->formatLocalized('%A, den %d.%m.%Y %H:%M') }}
                        @endif
                    @endif
                </p>
                <span class="date_descr">
                    {{ $date->description }}
                </span>
                @if($date->hasCommented())
                <span class="date_comment">
{{ trans('date.your_comment') }} {{ $date->getComment() }}
                </span>
                    @endif
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2 event-controls">
                @if($date->needsAnswer())
                    @if($date->hasBinaryAnswer() && $date->hasAnswered())
                        <div class="row">
                            <div class="col-xs-12">
                                <span class="slider-2d"
                                      data-function="changeAttendanceSlider"
                                      data-attend-url="{{ route('attendances.changeOwnAttendance', ['events_name' => $date->getShortNamePlural(), 'event_id' => $date->getId(), 'shorthand' => 'attend']) }}"
                                      data-excuse-url="{{ route('attendances.changeOwnAttendance', ['events_name' => $date->getShortNamePlural(), 'event_id' => $date->getId(), 'shorthand' => 'excuse']) }}">
                                    <input type="checkbox" {{ $date->isAttending() === 'yes' ? ' checked="checked"' : '' }} id="slider-attending-{{ $date->getShortName() }}-{{ $date->getId() }}">
                                    <label for="slider-attending-{{ $date->getShortName() }}-{{ $date->getId() }}">
                                        <span class="slider"></span>
                                        <i class="far fa-calendar-times" title="{{ trans('date.excuse') }}"></i>
                                        <i class="far fa-calendar-check" title="{{ trans('date.attend') }}"></i>
                                    </label>
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-xs-12">
                                <span class="button-set-2d">
                                    <a href="#"
                                       class="btn btn-2d btn-no {{ $date->hasAnswered() && $date->isAttending() == 'no' ? 'btn-pressed' : 'btn-unpressed' }}"
                                       data-url="{{ route('attendances.changeOwnAttendance', ['events_name' => $date->getShortNamePlural(), 'event_id' => $date->getId(), 'shorthand' => 'excuse'])}}"
                                       data-attendance="no">
                                        <i class="far fa-calendar-times"></i>
                                    </a>
                                    @if(!$date->hasBinaryAnswer())
                                    <a href="#"
                                       class="btn btn-2d btn-maybe {{ $date->hasAnswered() && $date->isAttending() == 'maybe' ? 'btn-pressed' : 'btn-unpressed' }}"
                                       data-url="{{ route('attendances.changeOwnAttendance', ['events_name' => $date->getShortNamePlural(), 'event_id' => $date->getId(), 'shorthand' => 'maybe'])}}"
                                       data-comment-url="{{ route('attendances.changeOwnAttendance', ['events_name' => $date->getShortNamePlural(), 'event_id' => $date->getId(), 'shorthand' => 'change'])}}"
                                       data-attendance="maybe">
                                        <i class="fas fa-question"></i>
                                    </a>
                                    @endif
                                    <a href="#"
                                       class="btn btn-2d btn-yes {{ $date->hasAnswered() && $date->isAttending() == 'yes' ? 'btn-pressed' : 'btn-unpressed' }}"
                                       data-url="{{ route('attendances.changeOwnAttendance', ['events_name' => $date->getShortNamePlural(), 'event_id' => $date->getId(), 'shorthand' => 'attend'])}}"
                                       data-attendance="yes">
                                        <i class="far fa-calendar-check"></i>
                                    </a>
                                </span>
                            </div>
                        </div>
                    @endif
                        <div class="row">
                            <div class="col-xs-3">
                                <span class="comment-btn-container">
                                    <a href="#" data-comment-url="{{ route('attendances.changeOwnAttendance', ['events_name' => $date->getShortNamePlural(), 'event_id' => $date->getId(), 'shorthand' => 'change'])}}"
                                       class="btn btn-2d" title="{{ trans('form.edit') }}">
                                        <i class="far fa-comment"></i>
                                    </a>
                                </span>
                            </div>
                            @if(isset($date->getEventOptions()['url']) && Auth::user()->isAdmin($date->getShortName()))
                                <div class="col-xs-3">
                                <span class="edit-btn-container">
                                    <a href="{{ $date->getEventOptions()['url'] }}" class="btn btn-2d" title="{{ trans('form.edit') }}">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                </span>
                                </div>
                            @endif
                        </div>
                @endif
            </div>
        </div>
    </div>
</div>