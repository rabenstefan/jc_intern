@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1>{{ trans('home.dashboard') }}</h1>

            <div class="panel panel-2d">
                <div class="panel-heading">{{ trans('home.welcome_title', ['name' => $user->first_name ]) }}</div>
                <div class="panel-body">
                    <div class="row">
                        @if($echo_needed)
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3" id="echo-needed-panel">
                                <div class="panel-heading  panel-heading-error">{{ trans('home.echo_needed') }}</div>
                                <div class="panel-element">
                                    <div class="panel-element-body">
                                        <p>{{ trans('home.echo_needed_body') }}</p>
                                        <br>
                                        <p>{{ trans('home.echo_semester', ['semester' => $next_semester->label]) }}</p>
                                        <a href="#"
                                           class="btn btn-2d btn-post"
                                           data-url="{{ route('users.updateSemester', $user->id) }}"
                                           data-callback-success="hideEchoNeededPanel">
                                            {{ trans('home.echo_semester_button') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="panel-heading  panel-heading-{{ $unanswered_panel['state'] }}">{{ trans('home.unanswered_heading') }}</div>
                            <div class="panel-element panel-element-background-icon panel-element-{{ $unanswered_panel['state'] }}">
                                <div class="panel-element-body">
                                    @if(0 === $unanswered_panel['count']['total'])
                                        <div class="panel-element-main panel-element-main-number ">&nbsp;</div>
                                        <p>{{ trans('home.unanswered_body_success') }}</p>
                                        <a href="{{ route('dates.index', ['view_type' => 'list']) }}">{{ trans('home.to_dates') }}</a>
                                    @else
                                        <div class="panel-element-main panel-element-main-number ">{{ $unanswered_panel['count']['total'] }}</div>
                                        @if(0 === $unanswered_panel['count']['unanswered']) {{-- Only 'Maybe's --}}
                                            <p>{{ trans('home.unanswered_body_maybe', $unanswered_panel['count']) }}</p>
                                            <a href="{{ route('dates.index', ['view_type' => 'list', 'hideByType' => ['birthday'], 'hideByStatus' => invert_date_statuses(['unanswered', 'maybe-going'])]) }}">{{ trans('home.show') }}</a><br>
                                            <a href="{{ route('dates.index', ['view_type' => 'list', 'showAll' => 'true']) }}">{{ trans('home.to_dates') }}</a>
                                        @elseif(0 === $unanswered_panel['count']['maybe']) {{-- Only 'Unanswered's --}}
                                            <p>{{ trans('home.unanswered_body_unanswered', $unanswered_panel['count']) }}</p>
                                            <a href="{{ route('dates.index', ['view_type' => 'list', 'hideByType' => ['birthday'], 'hideByStatus' => invert_date_statuses(['unanswered', 'maybe-going'])]) }}">{{ trans('home.show') }}</a><br>
                                            <a href="{{ route('dates.index', ['view_type' => 'list', 'showAll' => 'true']) }}">{{ trans('home.to_dates') }}</a>
                                        @else
                                            <p>{{ trans('home.unanswered_body', $unanswered_panel['count']) }}</p>
                                            <a href="{{ route('dates.index', ['view_type' => 'list', 'hideByType' => ['birthday'], 'hideByStatus' => invert_date_statuses(['unanswered'])]) }}">{{ trans('home.to_unanswered') }}</a><br>
                                            <a href="{{ route('dates.index', ['view_type' => 'list', 'hideByType' => ['birthday'], 'hideByStatus' => invert_date_statuses(['unanswered', 'maybe-going'])]) }}">{{ trans('home.to_unanswered_maybe') }}</a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3" title="{{ trans('home.next_gigs_addendum') }}">
                            <div class="panel-heading  panel-heading-{{ $next_gigs_panel['state'] }}">{{ trans('home.next_gigs_heading') }}</div>
                            <div class="panel-element panel-element-{{ $next_gigs_panel['state'] }}">
                                <div class="panel-element-body">
                                    <div class="panel-element-main panel-element-main-content ">{{ isset($next_gigs_panel['data'][0]) ? $next_gigs_panel['data'][0]->getStart()->diffForHumans() : '' }}</div>
                                    {{ trans('home.next_gigs_body') }}
                                    <ul>
                                        @foreach($next_gigs_panel['data'] as $gig)
                                            <li><strong>{{ $gig->getStart()->formatLocalized('%a., %d. %b.') }}</strong>
                                                @if(false === $gig->isAllDay())
                                                    {{ trans('home.at_time') }} {{ $gig->getStart()->formatLocalized('%H:%M') }}:
                                                @endif
                                                {{ str_shorten($gig->title, 10, '...')}}
                                                @if(true === $gig->hasPlace())
                                                    <br>
                                                    <a href="{{'https://www.google.com/maps/search/'}}@urlescape($gig->place)/" style="padding:0;" title="{{ trans('date.address_search') }}" target="_blank">({{ str_shorten($gig->place, 10, '...') }}  <i class="far fa-map"></i>)</a>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                    <a href="{{ route('dates.index', ['view_type' => 'list', 'hideByType' => invert_date_types(['gig'])]) }}">{{ trans('home.to_gigs') }}</a>
                                </div>
                            </div>
                        </div>@if(!$echo_needed)<div class="clearfix visible-sm-block"></div>@endif
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="panel-heading  panel-heading-{{ $next_rehearsals_panel['state'] }}">{{ trans('home.next_rehearsals_heading') }}</div>
                            <div class="panel-element panel-element-{{ $next_rehearsals_panel['state'] }}">
                                <div class="panel-element-body">
                                    <div class="panel-element-main panel-element-main-content ">{{ isset($next_rehearsals_panel['data'][0]) ? $next_rehearsals_panel['data'][0]->getStart()->diffForHumans() : '' }}</div>
                                    {{ trans('home.next_rehearsals_body') }}
                                    <ul>
                                        @foreach($next_rehearsals_panel['data'] as $rehearsal)
                                            <li><strong>{{ $rehearsal->getStart()->formatLocalized('%a., %d. %b.') }}</strong>
                                                @if(false === $rehearsal->isAllDay())
                                                    {{ trans('home.at_time') }} {{ $rehearsal->getStart()->formatLocalized('%H:%M') }}:
                                                @endif
                                                {{ str_shorten($rehearsal->title, 10, '...') }}
                                                @if(true === $rehearsal->hasPlace())
                                                    <br>
                                                    <a href="{{'https://www.google.com/maps/search/'}}@urlescape($rehearsal->place)/" style="padding:0;" title="{{ trans('date.address_search') }}" target="_blank">({{ str_shorten($rehearsal->place, 10, '...') }}  <i class="far fa-map"></i>)</a>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                <a href="{{ route('dates.index', ['view_type' => 'list', 'hideByType' => invert_date_types(['rehearsal'])]) }}">{{ trans('home.to_rehearsals') }}</a>
                                </div>
                            </div>
                        </div>@if(!$echo_needed)<div class="clearfix visible-md-block"></div>@endif
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="panel-heading  panel-heading-{{ $missed_rehearsals_panel['state'] }}">{{ trans('home.missed_rehearsals_heading') }}</div>
                            <div class="panel-element panel-element-{{ $missed_rehearsals_panel['state'] }}">
                                <div class="panel-element-main panel-element-body">
                                    <div class="panel-element-main panel-element-main-number ">{{ $missed_rehearsals_panel['count']['total'] }}</div>
                                    @if(0 === $missed_rehearsals_panel['count']['total'])
                                        <p>{{ trans('home.missed_rehearsals_body_success') }}</p>
                                    @else
                                        <p>{{ trans('home.missed_rehearsals_body', $missed_rehearsals_panel['count']) }}</p>
                                        @if($missed_rehearsals_panel['data']['over_limit'])
                                            <p>{{ trans('home.over_limit') }}</p>
                                        @endif
                                    @endif
                                    <a href="{{ route('dates.index', ['view_type' => 'list', 'hideByType' => invert_date_types(['rehearsal'])]) }}">{{ trans('home.to_future_rehearsals') }}</a>
                                </div>
                            </div>
                        </div>@if(!$echo_needed)<div class="clearfix visible-lg-block"></div><div class="clearfix visible-sm-block"></div>@endif
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="panel-heading  panel-heading-{{ $next_birthdays_panel['state'] }}">{{ trans('home.next_birthdays_heading') }}</div>
                            <div class="panel-element panel-element-{{ $next_birthdays_panel['state'] }}">
                                <div class="panel-element-body">
                                    <div class="panel-element-main panel-element-main-number ">{{ $next_birthdays_panel['count'] }}</div>
                                        {{ trans('home.upcoming_birthdays_body', ['count' => $next_birthdays_panel['count']])}}
                                        <ul>
                                        @foreach($next_birthdays_panel['data'] as $birthday)
                                            <li>{{ trans('home.birthday_name', ['name' => $birthday->getUser()->first_name]) }}
                                                <?php $diff = $today->diffInDays($birthday->getStart(), false) ?>
                                                @if($diff === 0)
                                                    <strong>{{ trans('home.today') }}</strong>
                                                @elseif($diff === 1)
                                                    <strong>{{ trans('home.tomorrow') }}</strong>
                                                    @elseif($diff === -1)
                                                    {{ trans('home.yesterday') }}
                                                    @elseif($diff < 0)
                                                    <em>{{ trans('home.past_in_days', ['days' => abs($diff)]) }}</em>
                                                    @else
                                                    {{ trans('home.future_in_days', ['days' => abs($diff)]) }}
                                                @endif
                                            </li>
                                        @endforeach
                                        </ul>
                                    <a href="{{ route('dates.index', ['view_type' => 'list', 'hideByType' => invert_date_types(['birthday'])]) }}">{{ trans('home.to_birthdays') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        function hideEchoNeededPanel() {
            $("#echo-needed-panel").hide(500, function() {
                $(this).remove()
            });
        }

        $(document).ready(function () {
            // Override link functions for POST-links.
            $("a.btn-post").click(function (event) {
                event.preventDefault();

                // Get the callback which can be set if the POST returns successfully.
                var callback_success = null;

                if(undefined !== $(this).data("callback-success")) {
                    callback_success = $(this).data("callback-success");

                    if (typeof window[callback_success] === "function") {
                        callback_success = window[callback_success];
                    }

                    if (typeof callback_success !== "function") {
                        callback_success = null;
                    }
                }

                // Request the url via post, include csrf-token and comment.
                $.post($(this).data("url"), {
                    _token: "{{ csrf_token() }}"
                }, function (data) {
                    // Success?
                    if (data.success) {
                        // Notify user.
                        $.notify(data.message, "success");

                        if (null !== callback_success) {
                            callback_success();
                        }
                    } else {
                        // Warn user.
                        $.notify(data.message, "danger");
                    }
                },
                "json");
            });
        });
    </script>
@endsection