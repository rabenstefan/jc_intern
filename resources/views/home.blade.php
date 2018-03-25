@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1>{{ trans('home.dashboard') }}</h1>

            <div class="panel panel-2d">
                <div class="panel-heading">{{ trans('home.welcome_title', ['name' => $user->first_name ]) }}</div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="panel-heading panel-heading-warning">{{ trans('home.unanswered') }}</div>
                            <div class="panel-element panel-element-warning">
                                <div class="panel-element-body">
                                {{ trans('home.unanswered_gigs', ['count' => Auth::user()->unansweredGigsCount()] ) }}
                                <a href="{{ route('date.index', ['view_type' => 'list', 'hideByType' => ['birthday'], 'hideByStatus' => \App\Http\Controllers\DateController::invertDateStatuses(['unanswered', 'maybe-going'])]) }}">{{ trans('home.all_unanswered_maybe') }}</a>
                                <a href="{{ route('date.index', ['view_type' => 'list', 'hideByType' => ['birthday'], 'hideByStatus' => \App\Http\Controllers\DateController::invertDateStatuses(['unanswered'])]) }}">{{ trans('home.all_unanswered') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="panel-heading">{{ trans('home.missed_rehearsals') }}</div>
                            <div class="panel-element panel-element-info">
                                <div class="panel-element-body">
                                {{ trans('home.missed_rehearsals', ['count' => Auth::user()->missedRehearsalsCount(), 'count_unexcused' => Auth::user()->missedRehearsalsCount(true)]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="panel-heading">{{ trans('home.next_rehearsal') }}</div>
                            <div class="panel-element panel-element-info">
                                <div class="panel-element-body">
                                {{ trans('home.next_rehearsal') }}
                                <a href="{{ route('date.index', ['view_type' => 'list', 'hideByType' => \App\Http\Controllers\DateController::invertDateTypes(['rehearsal'])]) }}">{{ trans('home.all_rehearsals') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="panel-heading">{{ trans('home.next_gig') }}</div>
                            <div class="panel-element panel-element-info">
                                {{ var_dump($next_gigs['data']->count()) }}
                                <div class="panel-element-body">
                                {{ trans('home.next_gig') }}
                                <a href="{{ route('date.index', ['view_type' => 'list', 'hideByType' => \App\Http\Controllers\DateController::invertDateTypes(['gig'])]) }}">{{ trans('home.all_gigs') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="panel-heading">{{ trans('home.next_birthdays') }}</div>
                            <div class="panel-element panel-element-info">
                                <div class="panel-element-body">
                                {{ trans('home.upcoming_birthdays', ['count' => $next_birthdays['count']])}}
                                    @foreach($next_birthdays['data'] as $birthday)
                                        <p>{{ $birthday->getFirstName() }}
                                            <?php $diff = $today->diffInDays($birthday->getStart(), false) ?>
                                            @if($diff === 0)
                                                {{ trans('home.today') }}
                                            @elseif($diff === 1)
                                                {{ trans('home.tomorrow') }}
                                                @elseif($diff === -1)
                                                {{ trans('home.yesterday') }}
                                                @elseif($diff < 0)
                                                {{ trans('home.past_in_days', ['days' => abs($diff)]) }}
                                                @else
                                                {{ trans('home.future_in_days', ['days' => abs($diff)]) }}
                                            @endif
                                        </p>
                                    @endforeach
                                <a href="{{ route('date.index', ['view_type' => 'list', 'hideByType' => \App\Http\Controllers\DateController::invertDateTypes(['birthday'])]) }}">{{ trans('home.all_birthdays') }}</a>
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
        $(document).ready(function () {
            // Override link functions for POST-links.
            $('a.btn-post').click(function (event) {
                event.preventDefault();

                // Request the url via post, include csrf-token and comment.
                $.post($(this).data('url'), {
                    _token: '{{ csrf_token() }}'
                }, function (data) {
                    // Success?
                    if (data.success) {
                        // Notify user.
                        $.notify(data.message, 'success');
                    } else {
                        // Warn user.
                        $.notify(data.message, 'danger');
                    }
                },
                'json');
            });
        });
    </script>
@endsection