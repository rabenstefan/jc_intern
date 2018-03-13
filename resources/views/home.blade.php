@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1>{{ trans('home.dashboard') }}</h1>

            <div class="panel panel-2d">
                <div class="panel-heading">{{ trans('home.welcome_title', ['name' => Auth::user()->first_name ]) }}</div>

                <div class="panel-body">
                    @if(\Auth::user()->isAdmin() && $semester_warning)
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="panel-element panel-element-warning">
                                    {{ trans('home.semester_warning') }}
                                    <a href="#" title="{{ trans('home.generate_semester') }}" class="btn btn-2d btn-post" data-url="{{ route('semester.generateNew') }}">
                                        <i class="fa fa-plus"></i>&nbsp;{{ trans('home.generate_semester') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="panel-element panel-element-info">
                                {{ trans('home.unanswered_gigs', ['count' => Auth::user()->unansweredGigsCount()] ) }}
                                <a href="{{ route('date.index', ['view_type' => 'list', 'hideByType' => ['answered', 'birthday']]) }}">{{ trans('home.all_unanswered') }}</a>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="panel-element panel-element-info">
                                {{ trans('home.missed_rehearsals', ['count' => Auth::user()->missedRehearsalsCount(), 'count_unexcused' => Auth::user()->missedRehearsalsCount(true)]) }}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="panel-element panel-element-info">
                                {{ trans('home.next_rehearsal', $next_rehearsal) }}
                                <a href="{{ route('date.index', ['view_type' => 'list', 'hideByType' => \App\Http\Controllers\DateController::invertDateTypes(['rehearsal'])]) }}">{{ trans('home.all_rehearsals') }}</a>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="panel-element panel-element-info">
                                {{ trans('home.next_gig', $next_gig) }}
                                <a href="{{ route('date.index', ['view_type' => 'list', 'hideByType' => \App\Http\Controllers\DateController::invertDateTypes(['gig'])]) }}">{{ trans('home.all_gigs') }}</a>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <!-- TODO what happens when there are no birthdays in range? -->
                            <div class="panel-element panel-element-info">{{ trans('home.upcoming_birthdays', ['count' => $upcoming_birthdays->count()])}}
                                <a href="{{ route('date.index', ['view_type' => 'list', 'hideByType' => \App\Http\Controllers\DateController::invertDateTypes(['birthday'])]) }}">{{ trans('home.all_birthdays') }}</a>
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