@extends('layouts.app')

@section('title'){{ trans('sheet.sheet_user_title', ['label' => $sheet->label, 'number' => $number]) }}@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <h1>{{ trans('sheet.sheet_user_title', ['label' => $sheet->label, 'number' => $number]) }}</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-2d">
                <div class="panel-heading">
                    {{ trans('sheet.sheet_user_edit') }}
                </div>
                <div class="panel-body">
                    {{ Form::open([
                        'action' => ['SheetController@sheetUserUpdate', $sheet->id, $number],
                        'method' => 'PUT',
                        'class' => 'sheet-user-form',
                    ]) }}
                    <div class="row">
                        <div class="col-xs-3 owner">
                            <label for="sheet-user-number">{{ trans('sheet.owner') }}</label>
                            <select id="sheet-user-owner" name="userid" class="form-control form-control-2d" data-current="{{ $user->name }}">
                            @foreach($usersArray as $id => $username)
                                <option value="{{ $id }}" @if ($username == $user->name) selected="selected" @endif>{{ $username }}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3 status">
                            <label for="sheet-user-number">{{ trans('sheet.status') }}</label>
                            <select id="sheet-user-status" name="status" class="form-control form-control-2d" data-current="{{ $user->pivot->status }}">
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" @if ($status == $user->pivot->status) selected="selected" @endif>{{ trans('sheet.' . $status) }}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3 number">
                            <label for="sheet-user-number">{{ trans('sheet.number') }}</label>
                            <input id="sheet-user-number" name="number" class="form-control form-control-2d" type="text" data-current="{{ $user->pivot->number }}" value="{{ $user->pivot->number }}">
                        </div>
                        <div class="col-xs-3 submit">
                            <br>
                            <button class="btn btn-2d">
                                {{ trans('sheet.change_data') }}
                            </button>
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
    $(document).ready(function () {

    });
</script>


@endsection