@extends('layouts.app')

@section('title'){{ trans('sheet.show_title', ['label' => $sheet->label]) }}@endsection

@section('content')
    {{-- Output role 'Musikalische Leitung' first. --}}
    <div class="row">
        <div class="col-xs-12">
            <h1>{{ trans('sheet.show_title', ['label' => $sheet->label]) }} <a class="btn btn-2d" href="{{ URL::action('SheetController@edit', [$sheet->id]) }}"><i class="fa fa-pencil-alt"></i></a></h1>
        </div>
    </div>
    @if (count($borrowed) > 0)
        @include('sheet.table', ['sheet' => $sheet, 'users' => $borrowed, 'name' => 'borrowed', 'count' => $sheet->borrowed->count() ])
    @endif
    @if (count($bought) > 0)
        @include('sheet.table', ['sheet' => $sheet, 'users' => $bought, 'name' => 'bought', 'count' => $sheet->bought->count() ])
    @endif
    @if (count($lost) > 0)
        @include('sheet.table', ['sheet' => $sheet, 'users' => $lost, 'name' => 'lost', 'count' => $sheet->lost->count() ])
    @endif

@endsection