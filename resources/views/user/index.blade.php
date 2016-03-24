@extends('layouts.app')

@section('title'){{ trans('user.index_title') }}@endsection

@section('content')
    <div class="row" id="{{ trans('nav.users') }}">
        <div class="col-xs-12">
            <h1>{{ trans('user.index_title') }}</h1>

            <?php
                // Get all users that have echoed in this semester.
                $semester = App\Semester::all()->last();
                $users = App\User::orderBy('voice_id')->where('last_echo', $semester->id)->get();

                // Set flags for voice grouping.
                $new_super_group = true;
                $first_super_group = true;
                $super_groups = [];
            ?>
            @foreach($users->groupBy('voice_id') as $users_in_voice)
                <?php
                    // Juggling with voice super groups to output the voices neatly.
                    $current_super_group = App\Voice::find($users_in_voice->first()->voice_id)->super_group;
                    $new_super_group = empty($last_super_group) || $last_super_group != $current_super_group;
                    $last_super_group = $current_super_group;

                    if ($new_super_group) {
                        $super_groups[$current_super_group] = App\Voice::find($last_super_group)->name;
                    }
                ?>
                {{-- End of the row. Do not output before first group, otherwise there is no row to end. --}}
                @if($new_super_group && !$first_super_group)
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                {{-- Start new row for new super group. --}}
                @if($new_super_group)
                <div class="row" id="{{ trans('nav.users') }}-{{ $super_groups[$current_super_group] }}">
                    <div class="col-xs-12">
                        <div class="panel panel-2d">
                            <div class="panel-heading">{{ $super_groups[$current_super_group] }}</div>

                            <div class="panel-body">
                @endif
                                <div class="col-xs-12">
                                    <div class="panel panel-2d">
                                        <div class="panel-heading">
                                            {{ App\Voice::find($users_in_voice->first()->voice_id)->name }}
                                        </div>

                                        @include('user.table', ['users' => $users_in_voice])
                                    </div>
                                </div>
                <?php $first_super_group = false; /* At this point no other group be the first group anymore. */ ?>
            @endforeach
        </div>
    </div>
    @if(Auth::user()->isAdmin())
    <div class="row">
        <div class="col-xs-12">
            <?php
            // Get all old users that have not echoed in this semester.
            $users = App\User::orderBy('voice_id')->where('last_echo', '<>', $semester->id)->get();
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
                                            {{ App\Voice::find($users_in_voice->first()->voice_id)->name }}
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
                @foreach($super_groups as $super_group)
                    <li><a href="#{{ trans('nav.users') }}-{{ $super_group }}">{{ $super_group }}</a></li>
                @endforeach
            </ul>
        </li>
        @if(Auth::user()->isAdmin())
        <li><a href="#{{ str_replace(' ', '-', trans('user.alumni')) }}">{{ trans('user.alumni') }}</a></li>
        @endif
    </ul>
@endsection