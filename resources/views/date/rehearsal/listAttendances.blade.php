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
                        <div id="tabs" role="navigation">
                            <ul class="nav nav-tabs">
                                <li class="nav-item"><a class="nav-link active" href="#tabs-presence">{{trans('date.rehearsal_check_presence')}}</a></li>
                                <li class="nav-item"><a class="nav-link" href="#tabs-excuse">{{trans('date.rehearsal_excuse_other')}}</a></li>
                            </ul>
                            <div id="tabs-presence">
                                @include('date.rehearsal.listAttendances.check_presence')
                            </div>
                            <div id="tabs-excuse">
                                @include('date.rehearsal.listAttendances.excuse')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js'){{-- TODO: Refactor these functions to one or more dedicated js-file(s), reducing very similar code in list and home --}}
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <script type="text/javascript">

                $( "#tabs" ).tabs();
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
        * Handles AJAX-call to change being excused for rehearsal.
        * Called via 'data-function' attribute.
        
         */
         function excuseMissing (sliderElement){
            if($(sliderElement).hasClass('inactive')) return false;
            $(sliderElement).addClass('inactive');

            // var excused = $(sliderElement).find('input[type="checkbox"]').prop('checked');
            var url = $(sliderElement).data('change-url');
            saveAttendance(url, sliderElement, '');
         }

        /**
         * Function only calls API via POST and handles the returned messages.
         *
         * @param url
         * @param sliderElement
         * @param currentlyPresent
         */
        function saveAttendance(url, sliderElement, currentlyPresent) {
            // Request the url via post, include csrf-token
            // 'currentlyPresent' means here: currently, as in before the slider was switched.
            // Hence: currentlyPresent === !presentAfterUpdatingDB === actuallyMissedThisRehearsal
            // Maybe we should rename some variables ...
            $.post(url, {
                    _token: '{{ csrf_token() }}',
                    missed: currentlyPresent
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
                'json').fail(function(xhr, status, error) {
                $.notify('{{ trans('date.attendance_not_saved') }}', 'danger');

                // TODO: maybe status codes are better here? However status messages should be consistent among browsers and they are passed by jquery
                if (error === 'Unauthorized' || error === 'No Reason Phrase') {
                    // Unauthorized (401): Session expired or user logged out in another tab
                    // No Reason Phrase (419): XSRF-Token verification failed or a problem with authorization
                    location.reload(true);
                } else if (error === 'Unprocessable Entity') {
                    // Validation failed (422)
                    try {
                        $.each(xhr.responseJSON, function (key, value) {
                            $.each(value, function (index, message) {
                                $.notify(message, 'danger');
                            });
                        });
                    } catch (err) {
                        // Unknwon error
                        $.notify('{{ trans('date.ajax_failed') }}' + status + ' ' + error, 'danger');
                        $.notify(err.name + ': ' + err.message, 'danger');
                    }
                } else {
                    // Unknwon error
                    $.notify('{{ trans('date.ajax_failed') }}' + status + ' ' + error, 'danger');
                }
            });
        }
    </script>
@endsection
