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
                                                <a href="{{ route('rehearsals.create') }}">{{ trans('nav.rehearsal_create') }}</a>
                                            </li>
                                        @endif
                                        @if (Auth::user()->isAdmin('gig'))
                                            <li>
                                                <a href="{{ route('gigs.create') }}">{{ trans('nav.gig_create') }}</a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <div class="panel-body" id="list-dates">
                            <form id="comment-form" class="modal" style="display: none;">
                                {!! Form::textInput2d('comment', null, []) !!}
                                {!! Form::submitInput2d([], trans('date.submit')) !!}
                            </form>
                            @include('date.settings_bar', [
                                'view_type'         => 'list',
                                'override_types'    => $override_types,
                                'override_statuses' => $override_statuses,
                                'override_show_all' => $override_show_all,
                                'date_types'        => $date_types,
                                'date_statuses'     => $date_statuses,
                                'view_types'        => $view_types
                            ])
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
         * Handles changes to a slider-element
         *
         * This method is called from the main.js via the "data-function" attribute on the switch for attendance.
         *
         * @param sliderElement
         */
        function changeAttendanceSlider (sliderElement) {
            // Do we need to excuse the user or is she attending?
            // If the slider's checkbox is "checked" we have to excuse her.
            var currentlyAttending = $(sliderElement).find('input[type="checkbox"]').prop('checked');
            var url = '';
            var attendance = '';

            if (currentlyAttending) {
                url = $(sliderElement).data('excuse-url');
                attendance = 'no';
            } else {
                url = $(sliderElement).data('attend-url');
                attendance = 'yes';
            }
            saveAttendance(url, null, function() {
                    changeEventDisplayState(sliderElement, attendance, true);
                });
        }

        /**
         * Function calls API via POST calls success_callback on success.
         *
         * @param url
         * @param attendance
         * @param comment
         * @param success_callback
         */
        function saveAttendance(url, comment = null, success_callback = null) {
            var data = {_token: '{{ csrf_token() }}'};

            if (null !== comment) {
                data['comment'] = comment;
            }

            // Request the url via post, include csrf-token and comment.
            $.post(url, data, function (reply) {
                // Success?
                if (reply.success) {
                    // Notify user.
                    $.notify(reply.message, 'success');
                    if (null !== success_callback) {
                        success_callback();
                    }
                } else {
                    // Warn user.
                    $.notify(reply.message, 'danger');
                }
            },
            'json').fail(function() {
                // Warn user.
                $.notify('{{ trans('date.ajax_failed') }}', 'danger');
            });
        }

        function changeEventDisplayState(button, attendance, slider = false) {
            if (true === slider) {
                $(button).find('input[type="checkbox"]').prop('checked', 'yes' === attendance);
            } else {
                $(button).siblings().addClass('btn-unpressed');
                $(button).siblings().removeClass('btn-pressed');

                $(button).addClass('btn-pressed');
                $(button).removeClass('btn-unpressed');
            }

            var eventNode = $(button).parents('.event');
            var titleNote = $(button).find('.title .not-going-note');

            if ('no' === attendance) {
                $(eventNode).addClass('event-not-going');
                $(titleNote).show();
            } else {
                $(eventNode).removeClass('event-not-going');
                $(titleNote).hide();
            }
        }


        $(document).ready(function () {
            // On submission of the form in the modal.
            $('#comment-form').submit(function (event) {
                event.preventDefault();

                saveAttendance($(this).attr('action'),
                    $('#comment').val()
                );

                $('#comment').val('');
                $.modal.close();
            });

            // On click of a gig attendance button.
            $('.button-set-2d > a.btn').click(function (event) {
                event.preventDefault();
                var button = this;
                saveAttendance($(this).data('url'), null, function() {changeEventDisplayState(button, $(button).data('attendance'), false);});

                if ($(this).data('attendance') === 'maybe') {
                    // Display modal to put in an excuse.
                    $('#comment-form').attr('action', $(this).data('comment-url'))
                        .modal({'escapeClose': false,
                            'clickClose': false,
                            'showClose': false});
                }
            });

            $('.comment-btn-container > a.btn').click(function (event) {
                event.preventDefault();
                var button = this;
                $('#comment-form').attr('action', $(this).data('comment-url')).modal();

            });
        });
    </script>
@endsection
