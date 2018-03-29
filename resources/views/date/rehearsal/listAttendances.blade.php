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
                                                                /** @var App\Voice $sub_voice */
                                                                $users = \App\User::getUsersOfVoice($sub_voice->id)
                                                                ?>
                                                                @foreach($users as $user)
                                                                        @include('date.rehearsal.listAttendances.user_entry', ['user' => $user, 'currentRehearsal' => $currentRehearsal])
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
    <script type="text/javascript">
        /**
         * Switches a slider to the opposite value. Sets the corresponding checkbox.
         *
         * @param sliderElement
         * @param currentState
         * @return Boolean success
         */
        function sliderSwitch (sliderElement, currentState) {
            $(sliderElement).find('input[type="checkbox"]').prop('checked', !currentState);
        }

        /**
         * Handles AJAX-call to change the attendance of a rehearsal.
         *
         * This method is called from the main.js via the "data-function" attribute on the switch for attendance.
         *
         * @param sliderElement
         */
        function changePresence (sliderElement) {
            if ($(sliderElement).hasClass('inactive')) return false;
            // Make slider inactive.
            $(sliderElement).addClass('inactive');

            // Do we need to excuse the user or is she attending?
            // If the slider's checkbox is "checked" we have to excuse her.
            var currentlyPresent = $(sliderElement).find('input[type="checkbox"]').prop('checked');

            var url = $(sliderElement).data('change-url');

            saveAttendance(url, sliderElement, currentlyPresent);
        }

        /**
         * Function only calls API via POST and handles the returned messages.
         *
         * @param url
         * @param sliderElement
         * @param currentlyAttending
         */
        function saveAttendance(url, sliderElement, currentlyPresent) {
            // Request the url via post, include csrf-token and comment.
            $.post(url, {
                    _token: '{{ csrf_token() }}',
                    missed: !currentlyPresent
                }, function (data) {
                    // Success?
                    if (data.success) {
                        // Get slider's current state and switch it and its event.
                        sliderSwitch(sliderElement, currentlyPresent);

                        // Make slider active again.
                        $(sliderElement).removeClass('inactive');
                    } else {
                        // Warn user.
                        $.notify(data.message, 'danger');

                        // Make slider active again.
                        $(sliderElement).removeClass('inactive');
                    }
                },
                'json');
        }
    </script>
@endsection
