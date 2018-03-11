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
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="panel-element panel-element-info">
                                {{ trans('home.unanswered_gigs', ['count' => Auth::user()->unansweredGigsCount()] ) }}
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
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="panel-element panel-element-info">
                                {{ trans('home.next_gig', $next_gig) }}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <!-- TODO what happens when there are no birthdays in range? -->
                            <div class="panel-element panel-element-info">{{ trans('home.upcoming_birthdays', ['count' => $upcoming_birthdays->count()])}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
