@extends('layouts.app')

@section('title'){{ trans('sheet.sheet_create_title') }}@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <h1>{{ trans('sheet.sheet_create_title') }}</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-2d">
                <div class="panel-heading">
                    <div class="panel-title pull-left">
                        {{ trans('sheet.sheet_create_title') }}
                    </div>
                    <div class="panel-title pull-right">
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                    {{ Form::open([
                        'action' => ['SheetController@store'],
                        'class' => 'sheet-create-form',
                    ]) }}
                    <div class="row">
                        <div class="col-xs-3 sheet-label">
                            <label for="sheet-label">{{ trans('sheet.label') }}</label>
                            <input type="text" name="label" id="sheet-label" class="form-control form-control-2d">
                        </div>
                        <div class="col-xs-3 sheet-amount">
                            <label for="sheet-amount">{{ trans('sheet.amount') }}</label>
                            <input type="number" name="amount" id="sheet-amount" class="form-control form-control-2d">
                        </div>
                        <div class="col-xs-3 submit">
                            <br>
                            <button class="btn btn-2d">{{ trans('sheet.add_series') }}</button>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')

<script>
</script>


@endsection