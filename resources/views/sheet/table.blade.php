<div class="row" id="{{  trans('sheet.'.$name) }}">
    <div class="col-xs-12">
        <div class="panel panel-2d">
            <div class="panel-heading">
                <div class="panel-title pull-left">{{ trans('sheet.'.$name) }}</div>
                <div class="panel-title pull-right">{{ $count }} / {{ $sheet->amount }}</div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-condensed sheet-{{ $name }}">
                        <thead>
                        <tr>
                            <th style="width: 11%;">{{ trans('form.name') }}</th>
                            <th style="width: 30%;">{{ trans('sheet.number') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $id => $user)
                            <tr class="user-row">
                                <td class="first-name">{{ HTML::linkAction('SheetController@sheetsPerUser', $user['name'], [$id], ['style' => 'padding: 0']) }}</td>
                                <td class="number">@foreach($user['numbers'] as $number)<span class="sheet-number" data-sheet-number="{{ $number }}">{{ HTML::linkAction('SheetController@sheetUser', $number, [$sheet->id, $number], ['style' => 'padding: 0']) }} @if ($number != end($user['numbers'])), @endif</span>@endforeach</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
