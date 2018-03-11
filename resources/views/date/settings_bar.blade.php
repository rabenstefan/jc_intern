<div class="row">
    <div class="col-xs-12 col-md-1">
        {{ trans('date.show_only') }}:
    </div>
    <div class="col-xs-12 col-md-5">
        @foreach(['birthdays', 'gigs', 'rehearsals'] as $button)
        <a class="btn btn-{{$button}} btn-2d {{in_array($button, $current_sets) ? 'btn-pressed' : ''}}"
           href="{{ route('date.index', ['view_type' => $view_type, 'sets' => array_xor_value($current_sets, $button)]) }}">
            {{ trans('date.'.$button) }}
        </a>
        @endforeach
        <a class="btn btn-all btn-2d" href="{{ route('date.index', ['view_type' => $view_type]) }}">
                {{ trans('nav.all') }}
        </a>
    </div>
    <div class="col-xs-12 visible-xs visible-sm">
        <br>
    </div>
    <div class="col-xs-12 col-md-6">
        @foreach(['calendar', 'list'] as $button)
        <a class="btn btn-{{$button}} btn-2d {{ $button === $view_type ? 'btn-pressed' : '' }}" href="{{ route('date.index', ['view_type' => $button, 'sets' => $current_sets]) }}">
            {{ trans('nav.dates_'.$button) }}
        </a>
        @endforeach
    </div>
</div>
<br>