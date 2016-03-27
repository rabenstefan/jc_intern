@extends('layouts.app')

@section('title'){{ trans('role.index_title') }}@endsection

@section('content')
    <?php Session::forget('_old_input'); /* Dirty hack so that the Forms do not get flashed. */ ?>
    <div class="row" id="{{ trans('role.index_title') }}">
        <div class="col-xs-12">
            <h1>{{ trans('role.index_title') }}</h1>

            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-2d">
                        <div class="panel-heading">{{ trans('role.index_title') }}</div>

                        <div class="panel-body">
                            {{ trans('role.instructions') }}
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-condensed">
                                            <thead>
                                            <tr>
                                                <th>{{ trans('role.label') }}</th>
                                                <th>{{ trans('role.can_plan_rehearsal') }}</th>
                                                <th>{{ trans('role.can_plan_gig') }}</th>
                                                <th>{{ trans('role.can_send_mail') }}</th>
                                                <th>{{ trans('role.can_configure_system') }}</th>
                                                <th>{{ trans('role.only_own_voice') }}</th>
                                                <th>&nbsp;</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach(\App\Role::all() as $role)
                                                @include('role.form', ['role' => $role])
                                            @endforeach
                                            @include('role.form', ['role' => null])
                                            </tbody>
                                        </table>
                                    </div>
                                    <a href="#" title="{{ trans('role.add_role') }}" class="btn btn-2d" id="add-role"><i class="fa fa-plus"></i>&nbsp;{{ trans('role.add_role') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    $('.swap-to-input').click(function () {
        $(this).hide();
        $(this).siblings('input').show();
    }).siblings('input').hide();

    $('#new-role').hide();

    $('#add-role').click(function () {
        $('#new-role').show();
    });
@endsection
