<?php $filters = \App\Http\Controllers\DateController::getFilterTypes();
$view_types = \App\Http\Controllers\DateController::getViewTypes(); ?>

<div class="row">
    <div class="col-xs-12 col-md-1">
        {{ trans('date.show_only') }}:
    </div>
    <div class="col-xs-12 col-md-5">
        @foreach($filters as $button)
            <?php $button_plural = str_plural($button); ?>
            <div id="toggle-{{$button_plural}}" class="btn btn-{{$button_plural}} btn-2d btn-pressed">
                {{ trans('date.'.$button_plural) }}
            </div>
        @endforeach
        <div id="toggle-all" class="btn btn-all btn-2d">
                {{ trans('nav.all') }}
        </div>
    </div>
    <div class="col-xs-12 visible-xs visible-sm">
        <br>
    </div>
    <div class="col-xs-12 col-md-6">
        @foreach($view_types as $button)
        <a class="btn btn-{{$button}} btn-2d {{ $button === $view_type ? 'btn-pressed' : '' }}" href="{{ route('date.index', ['view_type' => $button]) }}">
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
            @foreach($filters as $filter)
                dateFilters.availableFilters.push({'singular': '{{ $filter }}', 'plural': '{{ str_plural($filter) }}'});
            @endforeach

            dateFilters.prepareButtons();

            @if(null === $override_filters)
                dateFilters.applyAllFromCookie();
            @else
                // Some options to override filters have been passed as GET-parameters. We will now try to parse them to javascript
                dateFilters.showAll();
                @if(count($override_filters) === 0)
                    $.notify('{!! trans('date.filters_invalid') !!}', {className: 'warning', autoHideDelay: 5000});
                @else @foreach($override_filters as $singular)
                    <?php $plural = str_plural($singular); ?>
                    dateFilters.hideByType('{{$singular}}', '{{$plural}}');
                @endforeach @endif
            @endif
        });
    </script>
@endsection
