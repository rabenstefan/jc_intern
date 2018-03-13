
<tr class="sheet-per-user-row" style="border-bottom: 2px solid #ddd;border-top: 2px solid #ddd;">
    <th>{{ $label }}</th>
    <th>{{ trans('sheet.number') }}</th>
</tr>
@foreach($sheets as $id => $sheet)
<tr>
    <td>{{ $sheet['name'] }}</td>
    <td class="number">@foreach($sheet['numbers'] as $number)<span class="sheet-number" data-sheet-number="{{ $number }}">{{ HTML::linkAction('SheetController@sheetUser', $number, [$id, $number], ['style' => 'padding: 0']) }} @if ($number != end($sheet['numbers'])), @endif</span>@endforeach</td>
</tr>
@endforeach
