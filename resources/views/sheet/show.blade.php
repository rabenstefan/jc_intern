@extends('layouts.app')

@section('title'){{ trans('sheet.show_title', ['label' => $sheet->label]) }}@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
        </div>
    </div>
@endsection