@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1>{{ trans('home.dashboard') }}</h1>

            <div class="panel panel-2d">
                <div class="panel-heading">{{ trans('home.welcome_title', ['name' => Auth::user()->first_name ]) }}</div>

                <div class="panel-body">
                    <div class="panel-element">{{ trans('home.unanswered_gigs', ['count' => Auth::user()->unansweredGigsCount()] ) }}</div>
                    <div class="panel-element">{{ trans('home.missed_rehearsals', ['count' => Auth::user()->missedRehearsalsCount(), 'count_unexcused' => Auth::user()->missedRehearsalsCount(true)]) }}</div>
                    <div class="panel-element">{{ trans('home.next_rehearsal', $next_rehearsal) }}</div>
                    <div class="panel-element">{{ trans('home.next_gig', $next_gig) }}</div>
                    <!-- TODO what happens when there are no birthdays in range? -->
                    <div>{{ trans('home.upcoming_birthdays', ['count' => $upcoming_birthdays->count()])}}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
