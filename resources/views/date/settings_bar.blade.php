<?php $date_types = \App\Http\Controllers\DateController::getDateTypes();
$date_statuses = \App\Http\Controllers\DateController::getDateStatuses();
$view_types = \App\Http\Controllers\DateController::getViewTypes(); ?>

<div class="row">
    <div class="col-xs-12 col-md-1">
        {{ trans('date.show_only') }}:
    </div>
    <div class="col-xs-12 col-md-5">
        @foreach($date_types as $button)
            <?php $button_plural = str_plural($button); ?>
            <div id="toggle-{{$button_plural}}" class="btn btn-{{$button_plural}} btn-2d btn-pressed btn-date-type">
                {{ trans('date.'.$button_plural) }}
            </div>
        @endforeach
        <br>
            @if('list' === $view_type)
        @foreach($date_statuses as $button)
                <div id="toggle-{{$button}}" class="btn btn-{{$button}} btn-2d btn-pressed btn-date-status">
                    {{ trans('date.'.$button) }}
                </div>
            @endforeach
            <br>
            @endif
        <div id="toggle-all" class="btn btn-all btn-2d">
                {{ trans('date.all') }}
        </div>
    </div>
    <div class="col-xs-12 visible-xs visible-sm">
        <br>
    </div>
    <div class="col-xs-12 col-md-6">
        @foreach($view_types as $button)
        <a class="btn btn-{{$button}} btn-2d {{ $button === $view_type ? 'btn-pressed' : 'btn-unpressed' }}" href="{{ route('date.index', ['view_type' => $button]) }}">
            {{ trans('nav.dates_'.$button) }}
        </a>
        @endforeach
    </div>
</div>
<br>

@section('js')
    @parent

    <script type="text/javascript">
        $(document).ready(function () {

            @if('calendar' === $view_type)
                dateFilters.eventContainerIdentifier = '.fc-event';
            @elseif('list' === $view_type)
                dateFilters.eventContainerIdentifier = '.list-item';
            @endif

            dateFilters.activeFilters = {
                @foreach($date_types as $filter)
                '{{ $filter }}': {
                    'plural': '{{ str_plural($filter) }}',
                    'visible': true
                },
                @endforeach
                @foreach($date_statuses as $filter)
                '{{ $filter }}': {
                    'plural': '{{  $filter }}',
                    'visible': true
                },
                @endforeach
            };

            dateFilters.prepareButtons();

            function changePageUrl() {
                window.history.pushState({}, "", "{!! route('date.index', ['view_type' => $view_type]) !!}");
            }

            @if(true === $override_show_all)
                    // By construction, all dates should be visible already.
                    //dateFilters.prepareShowAll();
                    //dateFilters.applyAllFilters();
                    // The only thing left to do is reset the cookie.
                    dateFilters.setCookie();
                    changePageUrl();
            @else
                @if(count($override_types) === 0 && count($override_statuses) === 0)
                    dateFilters.readCookie();
                    dateFilters.applyAllFilters(false);
                @else
                    // Some options to override filters have been passed as GET-parameters. We will now try to parse them to javascript
                    @foreach($override_types as $singular)
                        <?php $plural = str_plural($singular); ?>
                        dateFilters.prepareHideFilter('{{$singular}}');
                    @endforeach
                    @foreach($override_statuses as $status)
                        dateFilters.prepareHideFilter('{{ $status }}');
                    @endforeach
                    dateFilters.applyAllFilters();
                    changePageUrl();
                @endif
            @endif
        });
    </script>
@endsection
