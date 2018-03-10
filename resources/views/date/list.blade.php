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
                            @include('date.settings_bar', ['view_type' => 'list', 'current_sets' => $current_sets])
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
         * Switches a slider to the opposite value.
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
         * Switch
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

        function saveAttendance(url, sliderElement, currentlyAttending, excuse) {
            // Request the url via post, include csrf-token and comment.
            $.post(url, {
                _token: '{{ csrf_token() }}',
                comment: excuse
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
                }

                $.modal.close();
            },
            'json');
        }

        $(document).ready(function () {
            $('#excuse-form').submit(function (event) {
                event.preventDefault();

                saveAttendance($(this).attr('action'),
                    $(this).data('sliderElement'),
                    $(this).data('currentlyAttending'),
                    $('#excuse').val());

                $('#excuse').val('');
            });
        });
    </script>
@endsection
