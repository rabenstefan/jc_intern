<div class="row">
    <div class="col-xs-12 col-md-1">
        {{ trans('date.show_only') }}:
    </div>
    <div class="col-xs-12 col-md-5">
        <a class="btn btn-2d" href="{{ route('date.index', ['view_type' => $view_type, 'set' => 'birthdays']) }}">
            {{ trans('form.birthday') }}
        </a>
        <a class="btn btn-2d" href="{{ route('date.index', ['view_type' => $view_type, 'set' => 'gigs']) }}">
            {{ trans('date.gigs') }}
        </a>
        <a class="btn btn-2d" href="{{ route('date.index', ['view_type' => $view_type, 'set' => 'rehearsals']) }}">
            {{ trans('date.rehearsals') }}
        </a>
        <a class="btn btn-2d" href="{{ route('date.index', ['view_type' => $view_type]) }}">
            {{ trans('nav.all') }}
        </a>
    </div>
    <div class="col-xs-12 visible-xs visible-sm">
        <br>
    </div>
    <div class="col-xs-12 col-md-6">
        <a class="btn btn-2d" href="{{ route('date.index', ['view_type' => 'calendar']) }}">
            {{ trans('nav.dates_calendar') }}
        </a>
        <a class="btn btn-2d" href="{{ route('date.index', ['view_type' => 'list']) }}">
            {{ trans('nav.dates_list') }}
        </a>
    </div>
</div>
<br>