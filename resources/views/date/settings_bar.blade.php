<?php $filters = ['birthday', 'gig', 'rehearsal'];
$view_types = ['calendar', 'list']; ?>

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

            function hideFilterByType(singular, plural) {
                @if ($view_type === 'list')
                $('.event.event-' + singular).parent('.row.list-item').hide();
                @elseif ($view_type === 'calendar')
                $('.fc-event.event-' + singular).parent('.fc-event-container').hide();
                @endif
                $('#toggle-' + plural).removeClass('btn-pressed');
                Cookies.set('EventFilterActive_'+singular, false, { expires: 1/12 });
            }

            function showFilterByType(singular, plural) {
                @if ($view_type === 'list')
                $('.event.event-' + singular).parent('.row.list-item').show();
                @elseif ($view_type === 'calendar')
                $('.fc-event.event-' + singular).parent('.fc-event-container').show();
                @endif
                $('#toggle-' + plural).addClass('btn-pressed');
                Cookies.set('EventFilterActive_'+singular, true, { expires: 1/12 });
            }

            function showAllFilters() {
                @foreach($filters as $singular)
                <?php $plural = str_plural($singular); ?>
                showFilterByType('{{$singular}}', '{{$plural}}');
                @endforeach
            }

            function toggleFilterByType(singular, plural) {
                // We check hasClass and not the Cookie, to not confuse people who use multiple tabs/windows.
                if ($('#toggle-' + plural).hasClass('btn-pressed')) {
                    // Previously shown. Hide!
                    hideFilterByType(singular, plural);
                } else {
                    // Hidden. Show!
                    showFilterByType(singular, plural);
                }
            }


            @foreach($filters as $singular)
                <?php $plural = str_plural($singular); ?>

                if (!(undefined === Cookies.get('EventFilterActive_{{$singular}}')) && ('false' === Cookies.get('EventFilterActive_{{$singular}}'))) {
                    hideFilterByType('{{$singular}}', '{{$plural}}');
                }

                $('#toggle-{{$plural}}').click(function () {
                    toggleFilterByType('{{$singular}}', '{{$plural}}');
                });
            @endforeach

            @if(null !== $override_filters)
            // showOnly-type arguments have been passed to PHP. We will now parse them
            showAllFilters();
            <?php $override_filters = array_intersect($filters, $override_filters); ?> {{-- Because never trust the client! --}}
                @if(count($override_filters) === 0)
                    $.notify('{!! trans('date.filters_invalid') !!}', {className: 'warning', autoHideDelay: 5000});
                @else @foreach(array_diff($filters, $override_filters) as $singular)
                    {{-- Sinve $override_filters was modified by array_intersect above, we now have
                      all instances of filters we want to hide --}}
                    <?php $plural = str_plural($singular); ?>
                    hideFilterByType('{{$singular}}', '{{$plural}}');
                @endforeach @endif
            @endif

            $('#toggle-all').click(function () {
                showAllFilters();
            });
        });
    </script>
@endsection
