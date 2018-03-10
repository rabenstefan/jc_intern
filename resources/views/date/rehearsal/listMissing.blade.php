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
                            {{ trans('date.rehearsal_listMissing_title') . ' ' . $currentRehearsal->title . ' (' . $currentRehearsal->start . ')' }}
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
                                                                    <div class="col-xs-6 col-sm-3 col-lg-2 names">
                                                                        {{ $user->first_name . ' ' . $user->last_name }}
                                                                    </div>
                                                                    <div class="col-xs-6 col-sm-3 col-lg-2 sliders">
                                                                        <span class="slider-2d" data-function="changeAttendance">
                                                                            <input type="checkbox"<?php echo $user->missedRehearsal($currentRehearsal->id) ? '' : ' checked="checked"'; ?> id="slider-attending-{{ $user->id }}">
                                                                            <label for="slider-attending-{{ $user->id }}">
                                                                                <i class="fa fa-calendar-times-o label-off" title="{{ trans('date.missed') }}"></i>
                                                                                <i class="fa fa-calendar-check-o label-on" title="{{ trans('date.attended') }}"></i>
                                                                            </label>
                                                                        </span>
                                                                    </div>
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
