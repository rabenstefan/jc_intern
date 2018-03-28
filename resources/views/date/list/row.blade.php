<?php
$applicable_filters = [$date->getShortName()];
$attending = '';

if (true === $date->needsAnswer()) {
    if (true === $date->currentUserHasAnswered()) {
        $attending = $date->currentUserIsAttending();
        switch ($attending) {
        case 'yes': case true:
            $applicable_filters[] = 'going';
            break;
        case 'no': case false:
            $applicable_filters[] = 'not-going';
            break;
        case 'maybe':
            $applicable_filters[] = 'maybe-going';
            break;
    }
        } else {
        $applicable_filters[] = 'unanswered';
    }
}

?>

<div class="row list-item" data-filters='["{{ implode('", "', $applicable_filters) }}"]'>
    <div class="col-xs-12 context-box-2d event event-{{ implode(" event-", $applicable_filters) }}">
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-md-8 col-lg-10">
                <h4 class="title">
                    {{ $date->getTitle() }}
                    <span class="not-going-note" style="display: <?php echo 'no' === $attending ? 'inline' : 'none'; ?>;">{{  ' &ndash; ' . trans('date.not_attending') }}</span>
                    @if($date->hasPlace())
                        <br>
                        {{ $date->place }}
                        <a href="https://www.google.com/maps/search/{{ $date->place }}/" title="{{ trans('date.address_search') }}" target="_blank" class="pull-right text-large">{{ trans('date.goto_maps') }} <i class="far fa-map"></i></a>
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
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2 event-controls">
                @if(true === $date->needsAnswer())
                @if(true === $date->binary_answer and true === $date->currentUserHasAnswered())
                    <div class="row">
                        <div class="col-xs-12">
                            <span class="slider-2d" data-function="changeAttendance" data-url="{{ route($date->getShortName() . '.changeOwnAttendance', [$date->getShortName() . '_id' => $date->getId()]) }}">
                                <input type="checkbox"<?php echo 'no' === $attending ? '' : ' checked="checked"'; ?> id="slider-attending-{{ $date->getShortName() }}-{{ $date->getId() }}">
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
                                <a href="#" class="btn btn-2d btn-no {{ $attending == 'no' ? 'btn-pressed' : 'btn-unpressed' }}" data-url="{{ route($date->getShortName() . '.changeOwnAttendance', [$date->getShortName() . '_id' => $date->getId()]) }}" data-attendance="no">
                                    <i class="far fa-calendar-times"></i>
                                </a>
                                @if(false === $date->binary_answer)
                                <a href="#" class="btn btn-2d btn-maybe {{ $attending == 'maybe' ? 'btn-pressed' : 'btn-unpressed' }}" data-url="{{ route($date->getShortName() . '.changeOwnAttendance', [$date->getShortName() . '_id' => $date->getId()]) }}" data-attendance="maybe">
                                    <i class="fas fa-question"></i>
                                </a>
                                @endif
                                <a href="#" class="btn btn-2d btn-yes {{ $attending == 'yes' ? 'btn-pressed' : 'btn-unpressed' }}" data-url="{{ route($date->getShortName() . '.changeOwnAttendance', [$date->getShortName() . '_id' => $date->getId()]) }}" data-attendance="yes">
                                    <i class="far fa-calendar-check"></i>
                                </a>
                            </span>
                        </div>
                    </div>
                @endif

                        <div class="row">
                            <div class="col-xs-3">
                                <span class="comment-btn-container">
                                <a href="#" class="btn btn-2d btn-comment" data-url="{{ route($date->getShortName() . '.changeOwnComment', [$date->getShortName() . '_id' => $date->getId()]) }}">
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

                @else
                    @if(isset($date->getEventOptions()['url']) && Auth::user()->isAdmin($date->getShortName()))
                        <div class="row">
                            <div class="col-xs-12">
                            <span class="edit-btn-container">
                                <a href="{{ $date->getEventOptions()['url'] }}" class="btn btn-2d" title="{{ trans('form.edit') }}">
                                    <i class="fa fa-pencil-alt"></i>
                                </a>
                            </span>
                            </div>
                        </div>
                    @endif
                    @endif

            </div>
        </div>
    </div>
</div>