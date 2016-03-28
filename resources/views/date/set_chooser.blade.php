<div class="row">
    <div class="col-xs-12 col-md-6">
        {{ trans('date.show_only') }}:
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
</div>
<br>