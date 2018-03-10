@extends('layouts.app')

@section('title'){{ trans('sheet.index_title') }}@endsection

@section('content')
    <div class="row" id="{{ trans('nav.sheet') }}">
        <div class="col-xs-12">
            <h1>{{ trans('sheet.index_title') }}</h1>

            <div class="row">
                <div class="col-xs-12 col-md-6">
                    {!! Form::textInput2d('search') !!}
                </div>
                @if(Auth::user()->isAdmin('sheet'))
                <div class="col-xs-12 col-md-6">
                    <br>
                    <a href="{{ route('sheet.create') }}" title="{{ trans('nav.sheet_create') }}" class="btn btn-2d">
                        <i class="fa fa-plus"></i>&nbsp;{{ trans('nav.sheet_create') }}
                    </a>
                </div>
                @endif
            </div>
            <br>

            {{-- Output role 'Musikalische Leitung' first. --}}
            <div class="panel panel-2d">
                @include('sheet.table', ['sheets' => $sheets])
            </div>
        </div>
    </div>
@endsection

@section('navlist')
    <ul class="nav">
        <li>
            <a href="#{{ trans('nav.users') }}">{{ trans('nav.users') }}</a>
            <ul class="nav">
                <li><a href="#{{ trans('nav.musical_leader') }}">{{ trans('nav.musical_leader') }}</a></li>
            </ul>
        </li>
        @if(Auth::user()->isAdmin())
        <li><a href="#{{ str_replace(' ', '-', trans('user.alumni')) }}">{{ trans('user.alumni') }}</a></li>
        @endif
    </ul>
@endsection

@section('js')
    <script type="text/javascript">
        function filterRowsByName(rows, name) {
            if (name.length === 0 || !(name.trim())) {
                $(rows).show().parents('table, .panel').show();
                return;
            }

            var names = name.toLowerCase().split(' ');
            var firstname = '';
            var lastname = '';
            var match = false;

            // Hide all rows.
            $(rows).hide().parents('table, .panel').hide();

            // Get all rows which first- and lastnames match all! of the search terms and show them.
            $(rows).filter(function () {
                firstname = $(this).find('.first-name').text().toLowerCase();
                lastname = $(this).find('.last-name').text().toLowerCase();

                match = false;
                $(names).each(function (index, value) {
                    match = (firstname.indexOf(value) >= 0) || (lastname.indexOf(value) >= 0);
                    if (!match) return false;
                });

                return match;
            }).show().parents('table, .panel').show();
        }

        $(document).ready(function () {
            // Make the search react to inputs (with timeout delay).
            $('#search').bind('input propertychange', function (e) {
                // If it's the propertychange event, make sure it's the value that changed.
                if (window.event && event.type == 'propertychange' && event.propertyName != 'value')
                    return;

                var input = $(this).val();

                // Clear any previously set timer before setting a fresh one
                window.clearTimeout($(this).data('timeout'));
                $(this).data('timeout', setTimeout(function () {
                    filterRowsByName($('.user-row'), input);
                }, 500));
            });
        });
    </script>
@endsection