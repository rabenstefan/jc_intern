@extends('layouts.app')

@section('title'){{ trans('date.rehearsal_listMissing_title') }}@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <h1>{{ trans('date.rehearsal_listMissing_title') }}</h1>

            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-2d">
                        <div class="panel-heading">
                            {{ $currentRehearsal->title . ' (' . $currentRehearsal->start->format('d.m.Y H:i') . ', ' . $currentRehearsal->start->diffForHumans() . ')' }}
                        </div>

                        <div id="attendance-list">
                            <?php
                            // Get all second level voices.
                            $voices = App\Voice::getParentVoices(App\Voice::getChildVoices());
                            ?>
                            @foreach($voices as $voice)
                                <div class="row" id="{{ trans('nav.users') }}-{{ $voice->name }}">
                                    <div class="col-xs-12">
                                        <div class="panel panel-2d">
                                            <div class="panel-heading">
                                                {{ $voice->name }}
                                            </div>

                                            <div class="panel-body">
                                                @foreach($voice->children as $sub_voice)
                                                    <div class="col-xs-12">
                                                        <div class="panel panel-2d">
                                                            <div class="panel-heading">
                                                                {{ $sub_voice->name }}
                                                            </div>


                                                            <div class="row">
                                                                <?php
                                                                $users = \App\User::getUsersOfVoice($sub_voice->id)
                                                                ?>
                                                                @foreach($users as $user)
                                                                        @include('date.listMissing.user_entry', ['user' => $user, 'currentRehearsal' => $currentRehearsal])
                                                                @endforeach
                                                            </div>
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
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection
