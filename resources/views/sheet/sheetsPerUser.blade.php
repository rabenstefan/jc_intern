@extends('layouts.app')

@section('title'){{ trans('sheet.sheets_per_user_title', ['name' => $user->name]) }}@endsection

@section('content')
    {{-- Output role 'Musikalische Leitung' first. --}}
    <div class="row">
        <div class="col-xs-12">
            <h1>{{ trans('sheet.sheets_per_user_title', ['name' => $user->name]) }}</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-2d">
                <div class="panel-heading">
                    <div class="panel-title pull-left">{{  trans('sheet.overview')   }}</div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-condensed sheet-overview">
                            <tbody>
                            @if (count($borrowed) > 0)
                                @include ('sheet.smallTable', ['label' => trans('sheet.borrowed_2'), 'sheets' => $borrowed])
                            @endif
                            @if (count($bought) > 0)
                                @include ('sheet.smallTable', ['label' => trans('sheet.bought'), 'sheets' => $bought])
                            @endif
                            @if (count($lost) > 0)
                                @include ('sheet.smallTable', ['label' => trans('sheet.lost'), 'sheets' => $lost])
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection