@extends('layouts.app')

@section('title'){{ trans('date.index_title') }}@endsection

@section('content')
    <div class="row" id="{{ trans('date.index_title') }}">
        <div class="col-xs-12">
            <h1>{{ trans('date.index_title') }}</h1>

            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-2d">
                        <div class="panel-heading">
                            {{ trans('date.index_title') }}

                            @if (Auth::user()->isAdmin('gig') || Auth::user()->isAdmin('rehearsal'))
                                <div class="pull-right">
                                    {!! Html::addButton(trans('date.add_date'), '#', ['dropdown-toggle'], ['data-toggle' => 'dropdown', 'aria-haspopup' => 'true', 'aria-expanded' => 'false']) !!}

                                    <ul class="dropdown-menu">
                                        @if (Auth::user()->isAdmin('rehearsal'))
                                            <li>
                                                <a href="{{ route('rehearsal.create') }}">{{ trans('nav.rehearsal_create') }}</a>
                                            </li>
                                        @endif
                                        @if (Auth::user()->isAdmin('gig'))
                                            <li>
                                                <a href="{{ route('gig.create') }}">{{ trans('nav.gig_create') }}</a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <div class="panel-body" id="list-dates">
                            <form id="excuse-form" class="modal" style="display: none;">
                                {!! Form::textInput2d('excuse', null, ['placeholder' => trans('form.optional')]) !!}
                                {!! Form::submitInput2d([], trans('date.excuse')) !!}
                            </form>
                            @include('date.settings_bar', ['view_type' => 'list', 'override_filters' => $override_filters])
                            @each('date.list.row', $dates, 'date')
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
            if ($(sliderElement).hasClass('inactive')) return false;

            $(sliderElement).find('input[type="checkbox"]').prop('checked', !currentState);
            return true;
        }

        /**
         * Switch the whole event-box to "off" (grey out so that the user knows he is not attending).
         *
         * @param sliderElement
         * @param currentState
         */
        function eventSwitch (sliderElement, currentState) {
            if ($(sliderElement).hasClass('inactive')) return false;

            var eventNode = $(sliderElement).parents('.event');
            var titleNote = $(eventNode).find('.title .not-going-note');

            if (currentState) {
                $(eventNode).addClass('event-not-going');
                $(titleNote).show();
            } else {
                $(eventNode).removeClass('event-not-going');
                $(titleNote).hide();
            }

            return true;
        }

        /**
         * Handles AJAX-call to change the attendance of a rehearsal.
         *
         * This method is called from the main.js via the "data-function" attribute on the switch for attendance.
         *
         * @param sliderElement
         */
        function changeAttendance (sliderElement) {
            // Make slider inactive.
            $(sliderElement).addClass('inactive');

            // Do we need to excuse the user or is she attending?
            // If the slider's checkbox is "checked" we have to excuse her.
            var currentlyAttending = $(sliderElement).find('input[type="checkbox"]').prop('checked');
            var url = '';
            if (currentlyAttending) {
                url = $(sliderElement).data('excuse-url');

                //excuse = prompt('{{ trans('date.excuse_comment') }}');
                $('#excuse-form').attr('action', url)
                    .data('currentlyAttending', currentlyAttending)
                    .data('sliderElement', sliderElement)
                    .modal();
            } else {
                url = $(sliderElement).data('attend-url');
                saveAttendance(url, sliderElement, currentlyAttending, null);
            }
        }

        /**
         * Function only calls API via POST and handles the returned messages.
         *
         * @param url
         * @param sliderElement
         * @param currentlyAttending
         * @param excuse
         */
        function saveAttendance(url, sliderElement, currentlyAttending, excuse) {
            // Request the url via post, include csrf-token and comment.
            $.post(url, {
                _token: '{{ csrf_token() }}',
                comment: excuse,
                attendance: currentlyAttending
            }, function (data) {
                // Success?
                if (data.success) {
                    // Notify user.
                    $.notify(data.message, 'success');

                    // Make slider active again.
                    $(sliderElement).removeClass('inactive');

                    // Get slider's current state and switch it and its event.
                    sliderSwitch(sliderElement, currentlyAttending);
                    eventSwitch(sliderElement, currentlyAttending);
                } else {
                    // Warn user.
                    $.notify(data.message, 'danger');

                    // Make slider active again.
                    $(sliderElement).removeClass('inactive');
                    $(this).removeClass('btn-pressed');
                }

                $.modal.close();
            },
            'json');
        }

        $(document).ready(function () {
            // On submission of the form in the modal.
            $('#excuse-form').submit(function (event) {
                event.preventDefault();

                saveAttendance($(this).attr('action'),
                    $(this).data('sliderElement'),
                    $(this).data('currentlyAttending'),
                    $('#excuse').val()
                );

                $('#excuse').val('');
            }).on($.modal.CLOSE, function () {
                // If the modal gets closed without entering the form reset and release switch.
                // Make all sliders active again.
                $('.slider-2d').removeClass('inactive');
            });

            // On click of a gig attendance button.
            $('.button-set-2d > a.btn').click(function (event) {
                event.preventDefault();

                //TODO: Restore pressed button if modal was unsuccessful.
                $('.button-set-2d > a.btn').removeClass('btn-pressed');

                $(this).addClass('btn-pressed');

                if ($(this).data('attendance') !== 'yes') {
                    // Display modal to put in an excuse.
                    $('#excuse-form').attr('action', $(this).data('url'))
                        .data('sliderElement', this)
                        .data('currentlyAttending', $(this).data('attendance'))
                        .modal();
                } else {
                    // Save attendance directly, without modal of excuse.
                    saveAttendance($(this).data('url'), null, 'yes', '');
                    eventSwitch(this, true);
                }
            });
        });
    </script>
@endsection
