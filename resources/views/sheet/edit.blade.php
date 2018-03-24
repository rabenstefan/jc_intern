@extends('layouts.app')

@section('title'){{ trans('sheet.sheet_edit_title', ['label' => $sheet->label]) }}@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <h1>{{ trans('sheet.sheet_edit_title', ['label' => $sheet->label]) }}</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-2d">
                <div class="panel-heading">
                    <div class="panel-title pull-left">
                        {{ $sheet->label }}
                    </div>
                    <div class="panel-title pull-right">
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                    {{ Form::open([
                        'action' => ['SheetController@update', $sheet->id],
                        'class' => 'sheet-edit-form',
                    ]) }}
                    <div class="row">
                        <div class="col-xs-3 sheet-label">
                            <label for="sheet-label">{{ trans('sheet.label') }}</label>
                            <input type="text" name="label" id="sheet-label" class="form-control form-control-2d" value="{{ $sheet->label }}">
                        </div>
                        <div class="col-xs-3 sheet-amount">
                            <label for="sheet-amount">{{ trans('sheet.amount') }}</label>
                            <input type="number" name="amount" id="sheet-amount" class="form-control form-control-2d" value="{{ $sheet->amount }}">
                        </div>
                        <div class="col-xs-3 submit">
                            <br>
                            <button class="btn btn-2d">{{ trans('sheet.edit') }}</button>
                            <button type="button" id="delete-sheet" class="btn btn-no btn-2d">{{ trans('sheet.delete') }}</button>
                        </div>
                    </div>
                    {{ Form::close() }}
                    {{ Form::open([
                        'action' => ['SheetController@destroy', $sheet->id],
                        'method' => 'delete',
                        'class'  => 'sheet-edit-form',
                        'id'     => 'destroy-sheet'
                    ]) }}
                    {{  Form::close() }}

                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')

    <script>
        $(document).ready(function () {
            $('button#delete-sheet').click(function(e){
                e.preventDefault();
                var answer = confirm(decodeURIComponent("{{ trans('sheet.sure_delete', ['label' => $sheet->label]) }}"));

                if ( answer ) {
                    $('form#destroy-sheet').submit();
                } else {
                    return false;
                }
            })
        });
    </script>


@endsection