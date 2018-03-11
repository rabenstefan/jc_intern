<?php $filters = ['birthday', 'gig', 'rehearsal'];
$view_types = ['calendar', 'list']; ?>

<div class="row">
    <div class="col-xs-12 col-md-1">
        {{ trans('date.show_only') }}:
    </div>
    <div class="col-xs-12 col-md-5">
        @foreach($filters as $button)
            <?php $button_plural = str_plural($button); ?>
            <div id="toggle-{{$button_plural}}" class="btn btn-{{$button_plural}} btn-2d {{in_array($button_plural, $current_sets) ? 'btn-pressed' : ''}}">
                {{ trans('date.'.$button_plural) }}
            </div>
        @endforeach
        <a class="btn btn-all btn-2d" href="{{ route('date.index', ['view_type' => $view_type]) }}">
                {{ trans('nav.all') }}
        </a>
    </div>
    <div class="col-xs-12 visible-xs visible-sm">
        <br>
    </div>
    <div class="col-xs-12 col-md-6">
        @foreach($view_types as $button)
        <a class="btn btn-{{$button}} btn-2d {{ $button === $view_type ? 'btn-pressed' : '' }}" href="{{ route('date.index', ['view_type' => $button, 'sets' => $current_sets]) }}">
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
            @foreach($filters as $button)
            <?php $button_plural = str_plural($button); ?>
            $('#toggle-{{$button_plural}}').click(function () {
                $('.event.event-{{$button}}').parent('.row.list-item').toggle()
                $('#toggle-{{$button_plural}}').toggleClass('btn-pressed');
            });
            @endforeach
        });
    </script>
@endsection
