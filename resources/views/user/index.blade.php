@extends('layouts.app')

@section('title'){{ trans('user.index_title') }}@endsection

@section('content')
    <div class="row" id="{{ trans('nav.users') }}">
        <div class="col-xs-12">
            <h1>{{ trans('user.index_title') }}</h1>

            @if(Auth::user()->isAdmin())
                <div class="row">
                    <div class="col-xs-12">
                        <div class="panel panel-2d">
                            <div class="panel-heading">{{ trans('user.add_user_title') }}</div>

                            <div class="panel-body">
                                <a href="{{ route('user.create') }}" title="{{ trans('user.add_user_title') }}" class="btn btn-2d"><i class="fa fa-plus"></i>&nbsp;{{ trans('user.add_user') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Output role 'Musikalische Leitung' first. --}}
            <div class="row" id="{{  trans('nav.musical_leader') }}">
                <div class="col-xs-12">
                    <div class="panel panel-2d">
                        <div class="panel-heading">{{ trans('nav.musical_leader') }}</div>

                        <div class="panel-body">
                            <div class="col-xs-12">
                                <div class="panel panel-2d">
                                    <div class="panel-heading">&nbsp;
                                    </div>
                                    @include('user.table', ['users' => App\User::getMusicalLeader()])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
                // Get all second level voices.
                $voices = App\Voice::getParentVoices(App\Voice::getChildVoices());
            ?>
            @foreach($voices as $voice)
                <div class="row" id="{{ trans('nav.users') }}-{{ $voice->name }}">
                    <div class="col-xs-12">
                        <div class="panel panel-2d">
                            <div class="panel-heading">{{ $voice->name }}</div>

                            <div class="panel-body">
                                @foreach($voice->children as $sub_voice)
                                    <div class="col-xs-12">
                                        <div class="panel panel-2d">
                                            <div class="panel-heading">
                                                {{ $sub_voice->name }}
                                            </div>

                                            @include('user.table', ['users' => \App\User::getUsersOfVoice($sub_voice->id)])
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @if(Auth::user()->isAdmin())
    <div class="row">
        <div class="col-xs-12">
            <?php
            // Get all old users that have not echoed in this semester.
            $users = App\User::orderBy('voice_id')->where('last_echo', '<>', \App\Semester::current()->id)->get();
            ?>
            <div class="row" id="{{ str_replace(' ', '-', trans('user.alumni')) }}">
                <div class="col-xs-12">
                    <div class="panel panel-2d panel-2d-grey">
                        <div class="panel-heading">{{ trans('user.alumni') }}</div>
                        <div class="panel-body">
                            @foreach($users->groupBy('voice_id') as $users_in_voice)
                                <div class="col-xs-12">
                                    <div class="panel panel-2d">
                                        <div class="panel-heading">
                                            {{ $users_in_voice->first()->voice->name }}
                                        </div>

                                        @include('user.table', ['users' => $users_in_voice])
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection

@section('navlist')
    <ul class="nav">
        <li>
            <a href="#{{ trans('nav.users') }}">{{ trans('nav.users') }}</a>
            <ul class="nav">
                <li><a href="#{{ trans('nav.musical_leader') }}">{{ trans('nav.musical_leader') }}</a></li>

                @foreach($voices as $voice)
                    <li><a href="#{{ trans('nav.users') }}-{{ $voice->name }}">{{ $voice->name }}</a></li>
                @endforeach
            </ul>
        </li>
        @if(Auth::user()->isAdmin())
        <li><a href="#{{ str_replace(' ', '-', trans('user.alumni')) }}">{{ trans('user.alumni') }}</a></li>
        @endif
    </ul>
@endsection