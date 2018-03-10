<div class="table-responsive">
    <table class="table table-condensed">
        <thead>
        <tr>
            <th style="width: 11%;">{{ trans('sheet.label') }}</th>
            <th style="width: 12%;">{{ trans('sheet.amount') }}</th>
            <th style="width: 30%;">{{ trans('sheet.borrowed') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($sheets as $sheet)
            <tr class="user-row">
                <td class="first-name">{{ $sheet->label }}</td>
                <td class="last-name">{{ $sheet->amount }}</td>
                <td class="last-name">{{ $sheet->borrowed }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>